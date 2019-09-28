<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use App\Companies;
use App\Tags;
use App\Import;
use App\Users;
use Log;
use Auth;

class ImportController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){                        
        $filter_tag = $request->tag;
        return view('import.list', ['filter_tag'=>$filter_tag, 'row'=>1]);        
    }
        
    public function destroy(Request $request){  

        $id = $request->id; 
        $report = Import::where('_id', $id)->delete();

        $request->session()->flash('success', 'Record is deleted successfully.');
        return redirect()->route('import-list');
    }
    
    public function deleteall(Request $request){        

        Import::truncate();

        $request->session()->flash('success', 'All records are deleted successfully.');
        return redirect()->route('import-list');
    }

    public function ajaxpage(Request $request){                                        

        $limit = intval($request->length); 
        $start=intval($request->start);

        if(!empty($request->search['value'])){
            $search = $request->search['value'];                        

            $importLog = Import::where('filename', 'LIKE', "%{$search}%")
            ->orWhere('import_type', 'LIKE', "%{$search}%")->get()->toArray();

            $recordsTotal = count( $importLog );
        }else{        
            $recordsTotal = count( Import::all()->toArray() );
            $importLog = Import::offset($start)->limit($limit)->get()->toArray();
        }

        $importLogArr = [];
        $rows=$start;
        foreach($importLog as $log){

            $url = route('download', ['filename' => $log['file_name']]);
            $download = "<a href='".$url."'>".$log['file_name']."</a>";           
            
            $url = route('import-delete', ['id' => $log['_id']]);
            $action = '
            <form id="delete-form-'.$log['_id'].'" method="post" action="'.$url.'"
            style="display: none;">
            <input type="hidden" name="_token" value="'.$request->session()->token().'"> 
            <input type="hidden" name="_method" value="DELETE">
            </form>
            <a class="btn link-action btn-danger" href="javascript://" onclick="
            if (confirm(\'Are You Sure, You want to delete this?\')){
            event.preventDefault();
            document.getElementById(\'delete-form-'.$log['_id'].'\').submit();
            } else{
            event.preventDefault();
            }">
            <i class="fa fa-trash-o">
            </i> Delete
            </a>';

            $updated_at = date("d M, Y h:i a", strtotime($log['created_at']));
            $company_name = (!empty($log['company_name'])?$log['company_name']:'-');
            $importLogArr[] = array(++$rows, $download, $company_name, ucfirst($log['import_type']), $updated_at, $action);             
        }

        $data = array(
        'draw'=>$request->draw, 
        'recordsTotal' => $recordsTotal, 
        'recordsFiltered' => $recordsTotal, 
        'data' => $importLogArr
        );
        return json_encode($data);                
    }
}
