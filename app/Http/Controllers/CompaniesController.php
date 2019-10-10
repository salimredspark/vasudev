<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;
use App\Tags;
use App\Users;
use App\Import;
use Log;
use Auth;

class CompaniesController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){

        $filter_tag = $request->tag;
        $tagClass = Tags::getAllTagCalss();                
        return view('companies.list', ['tags'=>$tagClass, 'filter_tag'=>$filter_tag, 'row'=>1, 'request' => $request]);        
    }

    public function create(){    

        $company  = Companies::all()->first();
        $skipColumns = array('_id','tag_name','company_name','updated_at','created_at','updated_by');
        $columns = array_keys($company->getAttributes());
        foreach($skipColumns as $kk) {
            $indexCompleted = array_search($kk, $columns);
            unset($columns[$indexCompleted]);
        }

        return view('companies.form', ['id' => false, 'company' => new Companies, 'columns' => $columns, 'row'=>1]);
    }

    public function update(Request $request){        
        $id = $request->id;
        $company = Companies::find($id); 

        $skipColumns = array('_id','tag_name','company_name','updated_at','created_at','updated_by');
        $columns = array_keys($company->getAttributes());
        foreach($skipColumns as $kk) {
            $indexCompleted = array_search($kk, $columns);
            unset($columns[$indexCompleted]);
        }

        return view('companies.form', ['id' => $id, 'company' => $company, 'columns' => $columns, 'row'=>1]);
    }

    public function store(Request $request){        
        $id = $request->id;       

        $this->validate($request,[
        'company_name' => 'required',
        'tag_name' => 'required'
        ]);               

        if(isset($id) && !empty($id)){
            $companies = Companies::find($id);
            $companies->updated_at = strtotime(date('Y-m-d h:i:s'));
            $companies->updated_by = Auth::user()->id;
        }else{
            $companies = new Companies($request->all());    
            $companies->updated_at = strtotime(date('Y-m-d h:i:s'));
            $companies->created_at = strtotime(date('Y-m-d h:i:s'));
            $companies->created_by = Auth::user()->id;
            $companies->updated_by = Auth::user()->id;
        }

        $companies->company_name = trim($request->company_name);
        $companies->tag_name = strtolower(str_replace('#','',trim($request->tag_name)));

        if(count($request->extra_fileds) > 0 ){
            foreach($request->extra_fileds as $field => $val){
                $companies->$field = trim($val);  
            }
        }

        $companies->save(); 

        if($id){
            $request->session()->flash('success','Company updated successfully!');
        }else{ 
            $request->session()->flash('success','Company created successfully!');
        }

        return redirect()->route('companies-list');
    }

    public function destroy(Request $request){  

        $id = $request->id; 
        $report = Companies::where('_id', $id)->delete();

        $request->session()->flash('success', 'Record is deleted successfully.');
        return redirect()->route('companies-list');
    }

    public function deleteall(Request $request){        

        Companies::truncate();

        $request->session()->flash('success', 'All records are deleted successfully.');
        return redirect()->route('companies-list');
    }

    public function ajaxpage(Request $request){                                        

        $limit = intval($request->length); 
        $start=intval($request->start);

        if(!empty($request->search['value'])){
            $search = $request->search['value'];                        

            $companies = Companies::where('tag_name', 'LIKE', "%{$search}%")
            ->orWhere('company_name', 'LIKE', "%{$search}%")->get()->toArray();
            $recordsTotal = count( $companies );
        }else{
            $recordsTotal = count( Companies::all()->toArray() );
            $companies = Companies::offset($start)->limit($limit)->get()->toArray();
        }

        $tags = Tags::getAllTagCalss();

        $companiesArr = [];
        $rows=$start;
        foreach($companies as $company){

            $tagsHtml = '';
            if ($company['tag_name'] != ""){
                foreach(explode(',', trim($company['tag_name'])) as $info){
                    $url = route('companies-filter', ['tag' => $info]);
                    $tagsHtml .= "<a href='".$url."' class='badge badge-pill badge-".$tags[array_rand($tags)]."'>$info</a>";
                }
            }

            $url = route('companies-delete', ['id' => $company['_id']]);
            $action = '<a class="btn btn-success link-action" href="'.route('companies-update', ['id' => $company['_id'] ]).'">Edit</a>
            <form id="delete-form-'.$company['_id'].'" method="post" action="'.$url.'"
            style="display: none;">
            <input type="hidden" name="_token" value="'.$request->session()->token().'"> 
            <input type="hidden" name="_method" value="DELETE">
            </form>
            <a class="btn link-action btn-danger" href="javascript://" onclick="
            if (confirm(\'Are You Sure, You want to delete this?\')){
            event.preventDefault();
            document.getElementById(\'delete-form-'.$company['_id'].'\').submit();
            } else{
            event.preventDefault();
            }">
            <i class="fa fa-trash-o">
            </i> Delete
            </a>';

            $updated_at = date("d M, Y h:i a", strtotime($company['updated_at']));
            $companiesArr[] = array(++$rows, $company['company_name'], $tagsHtml, $updated_at, $action);             
        }


        $data = array(
        'draw'=>$request->draw, 
        'recordsTotal' => $recordsTotal, 
        'recordsFiltered' => $recordsTotal, 
        'data' => $companiesArr
        );
        return json_encode($data);                
    }

    public function import(Request $request){
        return view('companies.import'); 
    }

    public function saveImport(Request $request){
        $file = $request->file('upload_file');

        $validator = $request->validate([
        'upload_file' => 'required',
        ]);        

        Log::channel('csvimportlog')->info('CSV file loaded!!');

        $fileName = $file->getClientOriginalName();                
        $fileExt = $file->getClientOriginalExtension();
        $fileTmpPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $fileMimeType = $file->getMimeType();

        //Move Uploaded File          
        $destinationPath = 'uploads'; //public/uploads
        $file->move($destinationPath, $file->getClientOriginalName());
        Log::channel('csvimportlog')->info('CSV file move to server!!');

        //save to database for log
        $saveUpload = new Import($request->all());                    
        $saveUpload->file_name = $fileName;
        $saveUpload->file_path = "uploads";
        $saveUpload->import_type = "company";
        $saveUpload->created_at = strtotime(date('Y-m-d h:i:s'));
        $saveUpload->created_by = Auth::user()->id;
        $saveUpload->save(); 
        Log::channel('csvimportlog')->info('CSV file save to database!!');

        //import after upload csv
        $file = public_path('uploads'.DIRECTORY_SEPARATOR.$fileName);
        $customerArr = $this->csvToArray($file);

        $externalTags = strtolower(str_replace("#","",trim($request->tag_name)));
        $externalTags = array_filter(explode(",",$externalTags));

        Log::channel('csvimportlog')->info('Start CSV file ready to read!!');

        foreach($customerArr as $column => $value)
        {
            if(empty(trim($value['company_name']))) continue;

            $arr1 = $arr2 = [];            
            $company = Companies::where([['company_name', '=', $value['company_name']]])->first();            
            if($company){          
                $existTags = $company->tag_name;                                
                $arr1 = array_filter(explode(",",$existTags));
                Log::channel('csvimportlog')->info('Update Company '.$company->company_name);                
            }
            else
            {
                $company = new Companies();
                Log::channel('csvimportlog')->info('New Company '.$value['company_name']);                
                $company->created_at = strtotime(date('Y-m-d h:i:s'));
                $company->created_by = Auth::user()->id;
            }

            //tags
            $csvImportTags = trim($value['city']).','.trim($value['pincode']).','.trim($value['locality']).',';
            $csvImportTags .= trim($value['level1']).','.trim($value['level2']).','.trim($value['level3']);
            $arr2 = array_filter(explode(",",strtolower($csvImportTags)));
            $finalTags = implode(",",array_unique(array_merge($arr1, $arr2, $externalTags)));

            $company->tag_name = strtolower($finalTags);

            $array_columns = array_keys($value);
            foreach($array_columns as $key){           
                $company->$key = $value[$key];
            } 

            $company->updated_at = strtotime(date('Y-m-d h:i:s'));                
            $company->updated_by = Auth::user()->id;
            $company->save(); 
            
            Log::channel('csvimportlog')->info('Saved Company '.$company->company_name);
        }

        Log::channel('csvimportlog')->info('Stop CSV file read!!');

        $request->session()->flash('success','CSV import successfully!');
        return redirect()->route('companies-list');
    }

    public function export(Request $request){
        $companies  = Companies::all()->first();
        $skipColumns = array('_id');
        $columns = array_keys($companies->getAttributes()); 
        foreach($skipColumns as $kk) {
            $indexCompleted = array_search($kk, $columns);
            unset($columns[$indexCompleted]);
        }

        return view('companies.export', [ "columns"=> $columns ] );
    }

    public function exportall(Request $request){

        $fields = $request->input('fields');
        $allcolumns = Companies::all(); // All columns
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build($allcolumns,  explode(",", $fields) )->download('companies_download_csv.csv');
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
} 