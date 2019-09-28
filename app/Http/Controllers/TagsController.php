<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;
use App\Customers;
use App\Tags;

class TagsController extends Controller{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){                
        $companies = Companies::all();
        $customers = Customers::all();
        $tags = Tags::all();
        $tagClass = Tags::getAllTagCalss();

        //get unique tags
        $uniqueTags = array();
        foreach ($customers as $customer){
            if ($customer->tag_name != ""){
                foreach(explode(',', trim($customer->tag_name)) as $tag){
                    if(!in_array($tag, $uniqueTags)){
                        $uniqueTags[] = $tag;
                    }
                }
            }
        }
        
        return view('tags.list', ["companies"=>$companies, 'allTags'=>$uniqueTags, 'tagClass'=>$tagClass]);        
    }

}