<?php

//installation
Route::get('install', function(){
    return View::make('install');
});

Route::post('install', function(){
    $config_path = app_path()."/config";
    $default_file = $config_path."/database_default.php";
    $output_file = $config_path."/database.php";
    $dbname = Input::get('dbname');
    $dbuser = Input::get('dbuser');
    $dbpw = Input::get('dbpw');
    try{
        $db = new PDO('mysql:host=localhost;dbname='.$dbname.';charset=utf8', $dbuser, $dbpw, array(PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $sth = $db->prepare("DROP TABLE IF EXISTS `pushmessage`");
        $sth->execute();
        $sth = $db->prepare("
        CREATE TABLE IF NOT EXISTS `pushmessage` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `identifier` text NOT NULL,
        `token` text NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");
        $sth->execute();
        $sth = $db->prepare("DROP TABLE IF EXISTS `queue`");
        $sth->execute();
        $sth = $db->prepare("
        CREATE TABLE IF NOT EXISTS `queue` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `queue_number` int(11) NOT NULL,
        `queue_type_id` int(11) NOT NULL,
        `identifier` text NOT NULL,
        `no_of_people` int(11) NOT NULL,
        `member_id` int(11) DEFAULT NULL,
        `entered` tinyint(4) NOT NULL DEFAULT '0',
        `cleared` int(11) NOT NULL DEFAULT '0',
        `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");
        $sth->execute();
        $sth = $db->prepare("DROP TABLE IF EXISTS `queue_type`");
        $sth->execute();
        $sth = $db->prepare("
        CREATE TABLE IF NOT EXISTS `queue_type` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `capacity` int(1) NOT NULL,
        `disabled` int(11) NOT NULL DEFAULT '1',
        `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");
        $sth->execute();
        $sth = $db->prepare("DROP TABLE IF EXISTS `setting`");
        $sth->execute();
        $sth = $db->prepare("
        CREATE TABLE IF NOT EXISTS `setting` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `key` text NOT NULL,
        `value` text NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");
        $sth->execute();
        $sth = $db->prepare("DROP TABLE IF EXISTS `user`");
        $sth->execute();
        $sth = $db->prepare("
        CREATE TABLE IF NOT EXISTS `user` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `firstname` varchar(255) NOT NULL,
        `lastname` varchar(255) NOT NULL,
        `email` text NOT NULL,
        `right` int(11) NOT NULL,
        `password` text NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");
        $sth->execute();
        $sth = $db->prepare("
        INSERT INTO `setting` (`id`, `key`, `value`) VALUES
        (1, 'sitename', 'Pizza Hut'),
        (2, 'uuid', '657A6D10-6492-4FA5-938D-DDEEAFACC3D6'),
        (3, 'beaconmajor', '1'),
        (4, 'beaconminor', '1'),
        (5, 'ip', 'localhost'),
        (6, 'port', '9999'),
        (7, 'pport', '5555'),
        (8, 'mallsystem', ''),
        (9, 'malluser', ''),
        (10, 'mallpw', '')");
        $sth->execute();
        shell_exec("cp ".escapeshellarg($default_file)." ".escapeshellarg($output_file));
        shell_exec('sed -i "" "s/\[dbname\]/'.$dbname.'/" '.$output_file);
        shell_exec('sed -i "" "s/\[dbuser\]/'.$dbuser.'/" '.$output_file);
        shell_exec('sed -i "" "s/\[dbpw\]/'.$dbpw.'/" '.$output_file);
        $user = new User();
        $user->email = Input::get('user');
        $user->right = '1';
        $user->password = Hash::make(Input::get('pw'));
        $user->save();
        return View::make('finish');
    }
    catch(PDOException $ex){
        return View::make('install')->with('wrong', true);
    }

});

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
Route::get('setting/beacon', function () {
    $uuid = DB::table('setting')->where('key', '=', 'uuid')->first();
    $major = DB::table('setting')->where('key', '=', 'beaconmajor')->first();
    $minor = DB::table('setting')->where('key', '=', 'beaconminor')->first();
    $response = array();
    $response['uuid'] = $uuid->value;
    $response['major'] = $major->value;
    $response['minor'] = $minor->value;
    return Response::json($response);
});



//normal routes
Route::group(['before' => array('auth.basic', 'api')], function() {
    Route::any('registerDevice', function(){
        $deviceToken = Input::get('deviceToken');
        if(!isset($deviceToken)){
            $response = array();
            $response['status'] = 'ERROR';
            $response['code'] = 500;
            $response['debug'] = 'Wrong parameters';
            return Response::json($response);
        }
        $token = str_replace(' ', '', $deviceToken);
        $token = str_replace('<', '', $token);
        $token = str_replace('>', '', $token);
        $identifier = hash('sha256', $token);
        $device = DB::table('pushmessage')->where('token', $token)->get(); //check existence
        if(count($device) == 0) { //insert an record if it does not exist
            DB::table('pushmessage')->insert(array('token' => $token, 'identifier' => $identifier));
        }
        $response = array();
        $response['identifier'] = $identifier;
        return Response::json($response);
    });
    Route::resource('queue', 'QueueController');
    Route::resource('queueType', 'QueueTypeController');
    Route::resource('user', 'UserController');
});

Route::resource('item', 'ItemController');
Route::resource('category', 'CategoryController');


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

    Route::get('admin/shop', array('uses' => 'AdminController@shopDetail'));
    Route::post('admin/shop', array('uses' => 'AdminController@shopEdit'));

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
