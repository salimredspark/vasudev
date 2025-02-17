<?php
namespace App;
use DB;
use Schema;
use App\Customers;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Companies extends Eloquent {

    protected $collection = 'companies'; //table name
    protected $connection = 'mongodb'; //db alias
    protected $primaryKey = '_id';

    protected  $fillable = array('company_name', 'tag_name', 'created_at');

    #const CREATED_AT = 'creation_date';
    #const UPDATED_AT = 'last_update';         
    
    public function getcustomers() 
    {
        return $this->hasOne(Customers::class);
    }
}