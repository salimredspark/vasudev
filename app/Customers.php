<?php
namespace App;
use DB;
use Schema;
use App\Companies;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Customers extends Eloquent {

    protected $collection = 'customers'; //table name
    protected $connection = 'mongodb'; //db alias
    protected $primaryKey = '_id';

    protected  $fillable = array('company_id', 'created_by');

    #const CREATED_AT = 'creation_date';
    #const UPDATED_AT = 'last_update' ;        

    public static function getDB(){
        $customers = DB::table('customers')->whereRaw("FIND_IN_SET('india', tag_name)  = 0 ")->get();
        return $customers; 
    }

    public function getcompanies() 
    {
        return $this->hasOne(Companies::class, '_id.toString()', 'company_id.toString()')->select(['company_name']);
    }
    
    public function getcreated() 
    {
        return $this->hasOne(User::class, '_id.toString()', 'created_by.toString()')->select(['name']);                
    }        
}