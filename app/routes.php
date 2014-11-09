<?php


//api authentication
Route::filter('api', function () {
    if(Auth::user()->right != 1 && Auth::user()->right != 2){
        $response = array();
        $response['status'] = 'ERROR';
        $response['code'] = 401;
        $response['debug'] = 'Unauthorized access';
        return Response::json($response);
    }
});

//queue settings route
Route::get('setting/getUUID', function () {
    $uuid = DB::table('queue_setting')->where('key', '=', 'uuid')->first();
    $major = DB::table('queue_setting')->where('key', '=', 'major')->first();
    $minor = DB::table('queue_setting')->where('key', '=', 'minor')->first();
    $ip = DB::table('queue_setting')->where('key', '=', 'ip')->first();
    $port = DB::table('queue_setting')->where('key', '=', 'port')->first();
    $response = array();
    $response['uuid'] = $uuid->value;
    $response['major'] = $major->value;
    $response['minor'] = $minor->value;
    $response['ip'] = $ip->value;
    $response['port'] = $port->value;
    return Response::json($response);
});


//normal routes
Route::group(['before' => array('auth.basic', 'api')], function() {
    Route::resource('queue', 'QueueController');
    Route::resource('queueType', 'QueueTypeController');
});

Route::resource('item', 'ItemController');
Route::resource('category', 'CategoryController');
Route::resource('user', 'UserController');


Route::get('queues/avgWaitingTime/{type}/year/{year}/month/{month}', array('uses' => 'QueueController@avgWaitingTime'));
Route::get('queues/entranceRate/{type}/year/{year}/month/{month}', array('uses' => 'QueueController@entranceRate'));
Route::get('queues/waitingTime/{type}', array('before'=> array('auth.basic', 'api'), 'uses' => 'QueueController@waitingTime'));
Route::get('queues/totalQueue/{type?}', array('before'=> array('auth.basic', 'api'), 'uses' => 'QueueController@totalQueue'));
Route::get('queues/currentNumber', array('before'=> array('auth.basic', 'api'), 'uses' => 'QueueController@currentNumber'));
Route::get('bill/getBillByTableID/{id}', array('uses' => 'BillController@getBillByTableID'));


//route group for admin
Route::get('admin', array('uses' => 'AdminController@login'));
Route::post('admin', array('uses' => 'AdminController@checkLogin'));
Route::group(['before' => array('auth.basic', 'api')], function() {

    Route::get('admin/setting', array('uses' => 'AdminController@setting'));
    Route::post('admin/setting', array('uses' => 'AdminController@updateSetting'));

    Route::get('admin/queue_type', array('uses' => 'AdminController@queue_type'));
    Route::get('admin/queue_type/create', array('uses' => 'AdminController@queue_typeDetail'));
    Route::get('admin/queue_type/{id}', array('uses' => 'AdminController@queue_typeDetail'));
    Route::post('admin/queue_type', array('uses' => 'AdminController@queue_typeCreate'));
    Route::post('admin/queue_type/{id}', array('uses' => 'AdminController@queue_typeEdit'));

    Route::get('queues/listQueue', array('uses' => 'QueueController@listQueue'));
    Route::get('queues/listQueue/{id}/type/{type}', array('uses' => 'QueueController@listQueue'));
    Route::get('admin/queue/{type?}', array('uses' => 'AdminController@queue'));
    Route::get('admin/queue/clear/{type}', array('uses' => 'AdminController@queueClear'));
    Route::get('admin/queue_stat/{type?}', array('uses' => 'AdminController@queueStat'));

    Route::get('admin/table', array('uses' => 'AdminController@table'));
    Route::get('admin/table/create', array('uses' => 'AdminController@tableDetail'));
    Route::post('admin/table', array('uses' => 'AdminController@tableCreate'));

    Route::get('admin/access', array('uses' => 'AdminController@access'));
    Route::get('admin/access/create', array('uses' => 'AdminController@accessDetail'));
    Route::get('admin/access/{id}', array('uses' => 'AdminController@accessDetail'));
    Route::post('admin/access', array('uses' => 'AdminController@accessCreate'));
    Route::post('admin/access/{id}', array('uses' => 'AdminController@accessEdit'));
    Route::any('admin/access/delete/{id}', array('uses' => 'AdminController@accessDelete'));

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
});
