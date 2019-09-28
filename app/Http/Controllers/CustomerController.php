<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;
use App\Customers;
use App\Tags;
use App\Import;
use App\Export;
use App\Users;
use Log;
use Auth;
use DB;
use timgws\QueryBuilderParser;
use Illuminate\Support\Facades\Input;

class CustomerController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){                        

        $filter_tag = $request->tag;
        $tagClass = Tags::getAllTagCalss();         
        return view('customer.list', ['tags'=>$tagClass, 'filter_tag'=>$filter_tag, 'row'=>1, 'request' => $request]);        
    }

    public function create(){    

        $customer = Customers::all()->first();
        $skipColumns = array('_id','tag_name','company_id','updated_at','created_at','updated_by','created_by');
        $columns = array_keys($customer->getAttributes());
        foreach($skipColumns as $kk) {
            $indexCompleted = array_search($kk, $columns);
            unset($columns[$indexCompleted]);
        }
        $companies = Companies::all()->sortBy("company_name");
        return view('customer.form', ['id' => false, 'customer' => new Customers,  "companies"=>$companies, 'columns' => $columns, 'row'=>1]);
    }

    public function update(Request $request){        
        $id = $request->id;
        $customer = Customers::find($id); 

        $skipColumns = array('_id','tag_name','company_id','updated_at','created_at','updated_by','created_by');
        $columns = array_keys($customer->getAttributes());
        foreach($skipColumns as $kk) {
            $indexCompleted = array_search($kk, $columns);
            unset($columns[$indexCompleted]);
        }

        $companies = Companies::all()->sortBy("company_name");
        return view('customer.form', ['id' => $id, 'customer' => $customer, "companies"=>$companies, 'columns' => $columns, 'row'=>1]);
    }

    public function store(Request $request){        
        $id = $request->id;       

        $this->validate($request,[
        'company_id' => 'required',
        'tag_name' => 'required'
        ]);               

        if(isset($id) && !empty($id)){
            $customer = Customers::find($id);
            $customer->updated_at = strtotime(date('Y-m-d h:i:s'));
            $customer->updated_by = Auth::user()->id;
        }else{
            $customer = new Customers($request->all());    
            $customer->updated_at = strtotime(date('Y-m-d h:i:s'));
            $customer->created_at = strtotime(date('Y-m-d h:i:s'));
            $customer->created_by = Auth::user()->id;
            $customer->updated_by = Auth::user()->id;
        }

        $customer->company_id = trim($request->company_id);
        $customer->tag_name = strtolower(str_replace('#','',trim($request->tag_name)));

        if(count($request->extra_fileds) > 0 ){
            foreach($request->extra_fileds as $field => $val){
                $customer->$field = trim($val);  
            }
        }

        $customer->save(); 

        if($id){
            $request->session()->flash('success','Customer updated successfully!');
        }else{ 
            $request->session()->flash('success','Customer created successfully!');
        }

        return redirect()->route('customer-list');
    }

    public function destroy(Request $request){  

        $id = $request->id; 
        $report = Customers::where('_id', $id)->delete();

        $request->session()->flash('success', 'Record is deleted successfully.');
        return redirect()->route('customer-list');
    }

    public function deleteall(Request $request){        

        Customers::truncate();

        $request->session()->flash('success', 'All records are deleted successfully.');
        return redirect()->route('customer-list');
    }

    public function ajaxpage(Request $request){                                        

        $limit = intval($request->length); 
        $start=intval($request->start);

        if(!empty($request->search['value'])){
            $search = $request->search['value'];                        

            $customers = Customers::where('tag_name', 'LIKE', "%{$search}%")
            ->orWhere('Number', 'LIKE', "%{$search}%")->orderBy('created_at', 'DESC')->get()->toArray();
            $recordsTotal = count( $customers );
        }else{        
            $recordsTotal = count( Customers::all()->toArray() );
            $customers = Customers::offset($start)->limit($limit)->orderBy('created_at', 'DESC')->get()->toArray();
        }

        $tags = Tags::getAllTagCalss();
        $customersArr = [];
        $rows=$start;
        foreach($customers as $customer){

            $tagsHtml = '';
            if ($customer['tag_name'] != ""){
                foreach(explode(',', trim($customer['tag_name'])) as $info){
                    $url = route('customer-filter', ['tag' => $info]);
                    $tagsHtml .= "<a href='".$url."' class='badge badge-pill badge-".$tags[array_rand($tags)]."'>$info</a>";
                }
            }

            $url = route('customer-delete', ['id' => $customer['_id']]);
            $action = '<a class="btn btn-success link-action" href="'.route('customer-update', ['id' => $customer['_id'] ]).'">Edit</a>
            <form id="delete-form-'.$customer['_id'].'" method="post" action="'.$url.'"
            style="display: none;">
            <input type="hidden" name="_token" value="'.$request->session()->token().'"> 
            <input type="hidden" name="_method" value="DELETE">
            </form>
            <a class="btn link-action btn-danger" href="javascript://" onclick="
            if (confirm(\'Are You Sure, You want to delete this?\')){
            event.preventDefault();
            document.getElementById(\'delete-form-'.$customer['_id'].'\').submit();
            } else{
            event.preventDefault();
            }">
            <i class="fa fa-trash-o">
            </i> Delete
            </a>';

            $updated_at = date("d M, Y h:i a", strtotime($customer['updated_at']));
            $customersArr[] = array(++$rows, $customer['Number'], $tagsHtml, $updated_at, $action);             
        }

        $data = array(
        'draw'=>$request->draw, 
        'recordsTotal' => $recordsTotal, 
        'recordsFiltered' => $recordsTotal, 
        'data' => $customersArr
        );
        return json_encode($data);                
    }

    public function import(Request $request){
        $companies = Companies::all()->sortBy("company_name");       
        return view('customer.import', ["companies"=>$companies]); 
    }

    public function saveImport(Request $request){
        $file = $request->file('upload_file');

        $validator = $request->validate([
        'upload_file' => 'required'        
        ]);       

        Log::channel('csvimportlog')->info('Customer CSV file loaded!!');

        $filename = $file->getClientOriginalName();                
        $extension = $file->getClientOriginalExtension();
        $fileTmpPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $fileMimeType = $file->getMimeType(); 

        // Valid File Extensions
        $valid_extension = array("csv");

        // 2MB in Bytes
        $maxFileSize = 9097152;

        if(in_array(strtolower($extension),$valid_extension))
        {
            $existCompanyTags = [];

            //Move Uploaded File          
            $location = 'uploads'; //public/uploads
            $file->move($location, $filename);
            Log::channel('csvimportlog')->info('CSV file move to server!!');
            $filepath = public_path($location."/".$filename);

            $company_name = '';            
            if($request->company_id)
            {
                $company_id = $request->company_id;                
                $companyObj = Companies::find($company_id);                                        
                $existCompanyTags = explode(",", $companyObj->tag_name);                
                $company_name = $companyObj->company_name;
            }

            //save to database for log
            $saveUpload = new Import($request->all());                    
            $saveUpload->file_name = $filename;
            $saveUpload->file_path = $location;
            $saveUpload->import_type = "customer";
            $saveUpload->company_name = $company_name;
            $saveUpload->created_at = strtotime(date('Y-m-d h:i:s'));
            $saveUpload->created_by = Auth::user()->id;
            $saveUpload->save(); 
            Log::channel('csvimportlog')->info('CSV file save to database!!');

            //import after upload csv
            $file = public_path($location.DIRECTORY_SEPARATOR.$filename);

            //external customer tags
            $externalTags = str_replace("#","",trim($request->tag_name));
            $externalTags = array_filter(explode(",",$externalTags));

            Log::channel('csvimportlog')->info('Start CSV file ready to read!!');

            $alreadyAssignedCompanyIds = [];
            $customerArr = $this->csvToArray($file);
            foreach($customerArr as $column => $value)
            {
                if(empty(trim($value['Number']))) continue;

                $arr1 = $arr2 = [];            
                $customerObj = Customers::where([['Number', '=', $value['Number']]])->first();            
                if($customerObj){          
                    $existTags = $customerObj->tag_name; //already saved tags in customer                                
                    $arr1 = array_filter(explode(",",$existTags));
                    Log::channel('csvimportlog')->info('Update Number '.$customerObj->number);                

                    $alreadyAssignedCompanyIds = array_filter(explode(",",$customerObj->company_id));                    
                }
                else{
                    $customerObj = new Customers();
                    Log::channel('csvimportlog')->info('New Number '.$value['Number']);                
                    $customerObj->created_at = strtotime(date('Y-m-d h:i:s'));
                    $customerObj->created_by = Auth::user()->id;
                }

                //customer tags
                $csvImportTags = trim($value['SenderId']).','.trim($value['Real Estate Supplier']); //getting from csv            
                $arr2 = array_filter(explode(",",$csvImportTags));
                $finalTags = implode(",",array_unique(array_merge($arr1, $arr2, $externalTags, $existCompanyTags)));

                //save company if selected
                if($request->company_id){
                    $companyObj->tag_name = $finalTags;
                    $companyObj->save();

                    if(!in_array($company_id, $alreadyAssignedCompanyIds)){
                        $alreadyAssignedCompanyIds[]=$company_id;
                    }
                }

                $customerObj->company_id = implode(",",$alreadyAssignedCompanyIds);
                $customerObj->tag_name = $finalTags;
                $customerObj->updated_at = strtotime(date('Y-m-d h:i:s'));                
                $customerObj->updated_by = Auth::user()->id;

                $array_columns = array_keys($value);                
                foreach($array_columns as $key){                    
                    $customerObj->$key = str_replace(" ","_",$value[$key]);
                } 
                $customerObj->save(); 

                Log::channel('csvimportlog')->info('Saved Customer '.$customerObj->number);
            }

            Log::channel('csvimportlog')->info('Stop CSV file read!!');

            $request->session()->flash('success','Customer CSV import successfully!');
        }
        else{
            $request->session()->flash('success','Invalid customer CSV file!');    
        }
        return redirect()->route('customer-list');
    }

    public function export(Request $request){
        $customer =  Customers::all()->first();      

        $skipColumns = array('_id');
        $columns = array_keys($customer->getAttributes()); 
        foreach($skipColumns as $kk) {
            $indexCompleted = array_search($kk, $columns);
            unset($columns[$indexCompleted]);
        }

        $allTags = Customers::select('tag_name')->get()->toArray();        
        if(count($allTags) > 0){
            foreach($allTags as $tag){
                $tagsInArr = explode(",",$tag['tag_name']);
                foreach($tagsInArr as $t){
                    $returnTagsArr[] = array('value'=>$t, 'label'=>$t);                
                }
            }
        }

        return view('customer.export', [ "columns"=> $columns, 'jsTags' => json_encode($returnTagsArr) ] );
    }

    public function exportQueryCounts(Request $request){

        $response = array();
        if(!empty($request['querybuilder']))
        {                            
            $table = DB::collection('customers');
            $qbp = new QueryBuilderParser( array( 'tag_name', 'Number', 'SenderId' ) );

            $query = $qbp->parse(json_encode($request['querybuilder']), $table);
            $rows = $query->get();

            $response = array(
            'status' => 'success',
            'counts' => count($rows),
            );
        }                
        return response()->json($response);
    }

    public function exportall(Request $request){{

            /* 
            $fields = $request->input('fields');
            $allcolumns = Customers::all(); // All columns
            $csvExporter = new \Laracsv\Export();
            $csvExporter->build($allcolumns,  explode(",", $fields) )->download('customers_download_csv.csv');
            */             


            $limit = intval($request->setLimit);
            $limitType = $request->setLimitType;
            $iseSelectAll = $request->selectAll;//is selected all if yes "on"

            $table = DB::collection('customers');
            $qbp = new QueryBuilderParser( array( 'tag_name', 'Number', 'SenderId' ) );

            $json = preg_replace("!\r?\n!", "", $request->querybuilder);                                    

            $query = $qbp->parse($json, $table);

            //$jsqbp = new JoinSupportingQueryBuilderParser($fields, $this->getJoinFields());

            if($limitType == 'all'){
                $rows = $query->offset(0)->limit($limit)->get();
            }            
            if($limitType == 'top'){
                $rows = $query->offset(0)->limit($limit)->orderBy('created_at', 'DESC')->get();
            }
            if($limitType == 'random'){
                $rows = $query->offset(0)->limit($limit)->orderBy("RAND()")->get(); 
            }

            $fields = $request->input('fields');
            
            $csvExporter = new \Laracsv\Export();
            $filename = 'export_customers_'.date('dMY').'_'.md5(rand(111,999)).'.csv';
            $csvExporter->build($rows,  $fields )->download($filename);
        }                                  
    }

    function csvToArray($filename = '', $delimiter = ','){
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    public function assignTagsInBulk(){
        $companies = Companies::all()->sortBy("company_name");
        return view('customer.assigntags', ["companies"=>$companies]);
    }

    public function saveAssignTagsInBulk(Request $request){
        $validator = $request->validate([
        'company_id' => 'required',
        'tag_name' => 'required',
        ]);

        //external customer tags
        $externalTags = str_replace("#","",trim($request->tag_name));
        $externalTags = array_filter(explode(",",$externalTags));

        $company_ids = $request->company_id;

        if(count($company_ids) > 0){
            foreach($company_ids as $company_id){
                $companyObj = Companies::find($company_id);                        
                $existCompanyTags = explode(",", $companyObj->tag_name);

                #$customerObj = Customers::where([['company_id', '=', $companyObj->_id]])->first();
                #$customers = Customers::whereRaw("find_in_set('".$company_id."',company_id)")->get();
                #$customers = \DB::table("customers")->select("customers.*")->whereRaw("find_in_set('".$company_id."',customers.company_id)")->get();
                #$customers = DB::table("customers")->whereRaw("FIND_IN_SET(?,tag_name)", [$search])->get(); //->toArray();
                $existCustomersTags = [];
                $customers = Customers::where('company_id', 'LIKE', "%{$company_id}%")->get()->toArray();                
                if(count($customers) > 0)
                {   
                    foreach($customers as $customer)
                    {  
                        $existCustomersTags = explode(",",$customer['tag_name']);
                        $finalTags = implode(",",array_unique(array_merge($existCompanyTags, $existCustomersTags, $externalTags)));

                        $customerObj = Customers::find($customer['_id']);                        
                        $customerObj->tag_name = $finalTags;
                        $customerObj->save();              
                    }                  
                }

                //save same tag in company
                $finalTags = implode(",",array_unique(array_merge($existCompanyTags, $existCustomersTags, $externalTags)));                
                $companyObj->tag_name = $finalTags;;
                $companyObj->save();
            }            
        }

        $request->session()->flash('success','Successfully tags assigned.');    
        return redirect()->route('customer-assign-tags');
    }


}