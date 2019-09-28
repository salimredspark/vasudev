<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;
use App\Tags;

class ReportController extends Controller{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){                
        $companies = Companies::all();
        $tags = Tags::all();
        $tagClass = Tags::getAllTagCalss();

        //get unique tags
        $uniqueTags = array();
        foreach ($companies as $company){
            if ($company->tag_name != ""){
                foreach(explode(',', trim($company->tag_name)) as $tag){
                    if(!in_array($tag, $uniqueTags)){
                        $uniqueTags[] = $tag;
                    }
                }
            }
        }
        
        return view('report.list', ["companies"=>$companies, 'allTags'=>$uniqueTags, 'tagClass'=>$tagClass]);        
    }

}