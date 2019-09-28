<?php
namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Tags extends Eloquent {

    protected $collection = 'tags'; //table name
    protected $connection = 'mongodb'; //db alias 

    public static function getAllTagCalss(){
        return ['primary','secondary','success','danger','warning','info','dark'];
    }

    public function getRandTagCalss(){
        $allTags = getAllTagCalss();
        return array_rand($allTags);
    }
}