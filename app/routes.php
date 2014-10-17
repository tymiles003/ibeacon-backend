<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//admin filter
Route::filter('admin', function () {
    if (Auth::check()) {
        if (Auth::user()->email != 'baalmok@gmail.com') {
            return 'bye';
        }
    } else {
        return 'bye';
    }
});

//queue settings route
Route::get('setting/getUUID', function () {
    $uuid = DB::table('queue_setting')->where('key', '=', 'uuid')->first();
    $major = DB::table('queue_setting')->where('key', '=', 'major')->first();
    $minor = DB::table('queue_setting')->where('key', '=', 'minor')->first();
    $response = array();
    $response['uuid'] = $uuid->value;
    $response['major'] = $major->value;
    $response['minor'] = $minor->value;
    return Response::json($response);
});

//normal routes
Route::resource('queue', 'QueueController');
Route::resource('item', 'ItemController');
Route::resource('category', 'CategoryController');
Route::resource('user', 'UserController');
Route::get('queues/avgWaitingTime/{type}', array('uses' => 'QueueController@avgWaitingTime'));
Route::get('queues/entranceRate/{type}', array('uses' => 'QueueController@entranceRate'));
Route::get('queues/waitingTime/{type}', array('uses' => 'QueueController@waitingTime'));
Route::get('queues/currentNumber', array('uses' => 'QueueController@currentNumber'));
Route::get('bill/getBillByTableID/{id}', array('uses' => 'BillController@getBillByTableID'));


//route group for admin
Route::get('admin/queue_type', array('uses' => 'AdminController@queue_type'));
Route::get('admin/queue_type/create', array('uses' => 'AdminController@queue_typeDetail'));

Route::get('queues/listQueue', array('uses' => 'QueueController@listQueue'));
Route::get('queues/listQueue/{id}/type/{type}', array('uses' => 'QueueController@listQueue'));
Route::get('admin/queue/{type?}', array('uses' => 'AdminController@queue'));
Route::get('admin/queue_stat/{type?}', array('uses' => 'AdminController@queueStat'));

Route::get('admin/table', array('uses' => 'AdminController@table'));
Route::get('admin/table/create', array('uses' => 'AdminController@tableDetail'));
Route::post('admin/table', array('uses' => 'AdminController@tableCreate'));

Route::get('admin/category', array('uses' => 'AdminController@category'));
Route::get('admin/category/create', array('uses' => 'AdminController@categoryDetail'));
Route::get('admin/category/{id}', array('uses' => 'AdminController@categoryDetail'));
Route::post('admin/category', array('uses' => 'AdminController@categoryCreate'));
Route::post('admin/category/{id}', array('uses' => 'AdminController@categoryEdit'));

Route::get('admin/item', array('uses' => 'AdminController@item'));
Route::get('admin/item/create', array('uses' => 'AdminController@itemDetail'));
Route::get('admin/item/{id}', array('uses' => 'AdminController@itemDetail'));
Route::post('admin/item', array('uses' => 'AdminController@itemCreate'));
Route::post('admin/item/{id}', array('uses' => 'AdminController@itemEdit'));


