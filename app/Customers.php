<?php
namespace App;
use DB;
use Schema;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Customers extends Eloquent {

    protected $collection = 'customers'; //table name
    protected $connection = 'mongodb'; //db alias

    protected  $fillable = array('company_id', 'created_by');

    #const CREATED_AT = 'creation_date';
    #const UPDATED_AT = 'last_update' ;        
    
    public static function getDB(){
          $customers = DB::table('customers')->whereRaw("FIND_IN_SET('india', tag_name)  = 0 ")->get();
          return $customers; 
    }
}