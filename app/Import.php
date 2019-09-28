<?php
namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Import extends Eloquent {

    protected $collection = 'uploaded_csv'; //table name
    protected $connection = 'mongodb'; //db alias
    
    protected  $fillable = array('file_name', 'file_path', 'created_by', 'created_at');
}