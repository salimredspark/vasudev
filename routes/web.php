<?php
use App\Companies;
use App\Tags; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
return view('welcome');
});*/

#set login as default
Route::get('/', function () {
    return view('home');
})->middleware('auth');

#menus after login
Auth::routes();

#home page or login page
Route::get('/home', 'HomeController@index')->name('home');

#manage companies
Route::get('companies', 'CompaniesController@index')->name('companies-list');
Route::get('companies/create', 'CompaniesController@create')->name('companies-create');
Route::get('companies/update/{id}', 'CompaniesController@update')->name('companies-update');
Route::delete('companies/destroy', 'CompaniesController@destroy')->name('companies-delete');
Route::get('companies/deleteall', 'CompaniesController@deleteall')->name('companies-deleteall');
Route::post('companies/store', 'CompaniesController@store')->name('companies-store');
Route::get('companies/filter/{tag}', 'CompaniesController@index')->name('companies-filter');
Route::get('companies/company/{id}', 'CompaniesController@index')->name('companies-company');

#import and export companies
Route::get('companies/import', 'CompaniesController@import')->name('companies-import');
Route::post('companies/saveImport', 'CompaniesController@saveImport')->name('companies-saveImport');
Route::get('companies/export', 'CompaniesController@export')->name('companies-export');
Route::get('companies/exportall', 'CompaniesController@exportall')->name('companies-exportall');

#manage tags
Route::get('tags', 'TagsController@index')->name('tags-list');

#manage customers
Route::get('customer', 'customerController@index')->name('customer-list');
Route::get('customer/create', 'customerController@create')->name('customer-create');
Route::get('customer/update/{id}', 'customerController@update')->name('customer-update');
Route::post('customer/store', 'customerController@store')->name('customer-store');
Route::delete('customer/destroy', 'customerController@destroy')->name('customer-delete');
Route::get('customer/deleteall', 'customerController@deleteall')->name('customer-deleteall');
Route::get('customer/filter/{tag}', 'customerController@index')->name('customer-filter');
Route::get('customer/assigntags', 'customerController@assignTagsInBulk')->name('customer-assign-tags');
Route::post('customer/saveAssignTagsInBulk', 'customerController@saveAssignTagsInBulk')->name('customer-save-assign-tags');

#import and export customers
Route::get('customer/import', 'customerController@import')->name('customer-import');
Route::post('customer/importprocess', 'customerController@importprocess')->name('customer-import-process');
Route::post('customer/saveImport', 'customerController@saveImport')->name('customer-saveImport');  

Route::get('customer/export', 'customerController@export')->name('customer-export');
Route::post('customer/exportQueryCounts', 'customerController@exportQueryCounts')->name('customer-exportcounts');
Route::post('customer/exportall', 'customerController@exportall')->name('customer-exportall');


#manage import log 
Route::get('import', 'importController@index')->name('import-list');
Route::delete('import/destroy', 'importController@destroy')->name('import-delete');
Route::get('import/deleteall', 'importController@deleteall')->name('import-deleteall');

#manage reports
Route::get('/report', 'reportController@index')->name('report');

#download sample file
Route::get('download/sample/{filename}', function($filename)
{       
    #$file_url = url('/uploads/'. $filename);
    $file_path = public_path('uploads'.DIRECTORY_SEPARATOR.'sample'.DIRECTORY_SEPARATOR. $filename); //. DS .'uploads'. DS .$filename;

    if (file_exists($file_path))
    {
        // Send Download
        return Response::download($file_path, $filename, [
        'Content-Length: '. filesize($file_path)
        ]);
    }
    else
    {
        // Error
        exit('Requested file does not exist on our server!');
    }
})
->where('filename', '[A-Za-z0-9\-\_\.]+')->name('download-sample');

#download any file
Route::get('uploads/{filename}', function($filename)
{       
    $file_path = public_path('uploads'.DIRECTORY_SEPARATOR.$filename);             

    if (file_exists($file_path))
    {
        // Send Download
        return Response::download($file_path, $filename, [
        'Content-Length: '. filesize($file_path)
        ]);
    }
    else
    {
        // Error
        exit('Requested file does not exist on our server!');
    }
})
->where('filename', '[A-Za-z0-9\-\_\.]+')->name('download');

//ajax for data table in companies table
Route::get('companies/ajaxpage', 'CompaniesController@ajaxpage')->name('companies-ajax');
Route::get('customer/ajaxpage', 'customerController@ajaxpage')->name('customer-ajax');
Route::get('import/ajaxpage', 'importController@ajaxpage')->name('import-ajax');







/*Route::get ( 'companies', function () {
$companies = Companies::all();
$tagClass = Tags::getAllTagCalss();
return view ( 'companies.list' )->withCompanies( $companies )->withTags($tagClass);
} )->name('companies-list');


#export csv
Route::get('export', 'exportController@index')->name('export-csv');
Route::get('export/index', 'ExportController@index')->name('export-create'); //export form page
Route::get('export-all', 'ExportController@exportall')->name('export-all');
*/
