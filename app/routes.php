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
Route::filter('admin', function()
{
	if (Auth::check()){
    	if (Auth::user()->email != 'baalmok@gmail.com')
	    {
	        return 'bye';
	    }
	}else{
		return 'bye';
	}
});

//normal routes
Route::resource('queue', 'QueueController');
Route::resource('item', 'ItemController');
Route::resource('category', 'CategoryController');
Route::resource('user', 'UserController');
Route::get('queues/currentNumber', array('uses' => 'QueueController@currentNumber'));
Route::get('bill/getBillByTableID/{id}', array('uses' => 'BillController@getBillByTableID'));


//route group for admin
Route::get('queues/listQueue', array('uses' => 'QueueController@listQueue'));
Route::get('queues/listQueue/{id}', array('uses' => 'QueueController@listQueue'));
Route::get('admin/queue', array('uses' => 'AdminController@queue'));
Route::get('admin/item', array('uses' => 'AdminController@item'));
Route::get('admin/item/create', array('uses' => 'AdminController@itemDetail'));
Route::get('admin/item/{id}', array('uses' => 'AdminController@itemDetail'));
Route::post('admin/item', array('uses' => 'AdminController@itemCreate'));
Route::post('admin/item/{id}', array('uses' => 'AdminController@itemEdit'));