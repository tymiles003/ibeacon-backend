<?php

class AdminController extends \BaseController
{

    public function shopDetail()
    {
        $ch = curl_init(Setting::getMallSystem().'/category');
        curl_setopt($ch, CURLOPT_USERPWD, Setting::getMallUser().":".Setting::getMallpw());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $cats = json_decode($result);
        foreach ($cats as $cat) {
            $catOption[$cat->id] = $cat->catname;
        }
        $ch = curl_init(Setting::getMallSystem().'/shop/1');
        curl_setopt($ch, CURLOPT_USERPWD, Setting::getMallUser().":".Setting::getMallpw());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $shop = json_decode($result);
        return View::make('admin.shop')->with('catOption', $catOption)->with('shop', $shop);
    }

    public static function http_build_query_for_curl($arrays, &$new = array(), $prefix = null)
    {
        if (is_object($arrays)) {
            $arrays = get_object_vars($arrays);
        }

        foreach ($arrays AS $key => $value) {
            $k = isset($prefix) ? $prefix . '[' . $key . ']' : $key;
            if (is_array($value) OR is_object($value)) {
                http_build_query_for_curl($value, $new, $k);
            } else {
                $new[$k] = $value;
            }
        }
    }
    public function shopEdit()
    {
        $data_string = json_encode(Input::all());
        if (!empty($_FILES)) {
            //debug ($_FILES);
            //debug ($curl_post_data);
            //debug(realpath('./post.php'));
            //debug (realpath($_FILES[]);
            foreach ($_FILES as $key=>$_FILE) {
                if (!empty($_FILE['name'])) {
                    $_POST[$key] = '@'.$_FILE['tmp_name'].';filename='.$_FILE['name'];
                }
            }
        }
        AdminController::http_build_query_for_curl($_POST, $newArray);
        $ch = curl_init(Setting::getMallSystem().'/shop/1');
        curl_setopt($ch, CURLOPT_USERPWD, Setting::getMallUser().":".Setting::getMallpw());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
        $post = array('name'=>'ss','logo'=>'@/Users/frmok26/Documents/mall/public/assets/shop/s.jpg');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $newArray);
        $result = curl_exec($ch);
        curl_close ($ch);
        return http_build_query($_POST);
    }

    public function setting()
    {
        return View::make('admin.setting');
    }

    public function updateSetting()
    {
        foreach (Input::except('_token') as $key=>$value){
            Cache::forget($key);
            if($key == 'mallpw' && empty($value)){
                continue;
            }
            DB::table('setting')
            ->where('key', $key)
            ->update(array('value' => $value));
        }
        return View::make('admin.setting')->with('message', 'Edited successfully');
    }
    public function login()
    {
        return View::make('admin.login');
    }

    public function checkLogin()
    {
        if (Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password'))))
        {
            if(Auth::user()->right != 1){
                return View::make('admin.login')->with('wrong', true);
            }
            return Redirect::to('admin/setting');
        }else{
            return View::make('admin.login')->with('wrong', true);
        }
    }
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function queue()
    {
        $type = Route::input('type');
        if (!isset($type)) {
            $type = 1;
        }
        $currentNumber = Queues::currentNumber($type);
        $queues = Queues::where('queue_type_id', '=', $type)
        ->where('cleared', '=', 0)
        ->where(function ($query) {
            $query->where('entered', '=', 0)
            ->orWhere('entered', '=', 1);
        })
        ->get()
        ->sortBy('queue_number');
        $maxQueueNumber = DB::table('queue')
        ->where('queue_type_id', '=', $type)
        ->where('cleared', '=', 0)
        ->max('queue_number');
        $response = array();
        $response['type'] = $type;
        $response['queues'] = $queues;
        if (!isset($maxQueueNumber)) {
            $maxQueueNumber = 0;
        }
        $response['maxQueueNumber'] = $maxQueueNumber;
        return View::make('admin.queue')->with($response);
    }

    public function queueStat()
    {
        $response['type'] = Route::input('type');
        return View::make('admin.queue_stat')->with($response);
    }

    public function queueClear()
    {
        $type = Route::input('type');
        DB::table('queue')
        ->where('queue_type_id', '=', $type)
        ->update(array('cleared' => 1));
        return Redirect::to('admin/queue/'.$type);
    }

    public function queue_type()
    {
        $queueTypes = QueueType::all();
        $response = array();
        $response['queueTypes'] = $queueTypes;
        return View::make('admin.queue_type')->with($response);
    }


    public function queue_typeDetail()
    {
        if (Route::input('id') > 0) {
            $queueType = QueueType::find(Route::input('id'));
            return View::make('admin.queue_type_edit')->with('queueType', $queueType);
        } else {
            return View::make('admin.queue_type_create');
        }
    }

    public function queue_typeCreate()
    {
        $queueType = new QueueType();
        $post_data = Input::all();
        $queueType->fill($post_data);
        $queueType->disabled = 1;
        $queueType->save();
        return View::make('admin.queue_type_edit')->with('queueType', $queueType)->with('message', 'Added successfully');
    }

    public function queue_typeEdit()
    {
        $post_data = Input::all();
        $queueType = QueueType::find(Input::get('id'));
        $queueType->fill($post_data);
        $queueType->save();
        return View::make('admin.queue_type_edit')->with('queueType', $queueType)->with('message', 'Edited successfully');
    }

    public function table()
    {
        $tables = Table::all();
        $response = array();
        $response['tables'] = $tables;
        return View::make('admin.table')->with($response);
    }

    public function tableDetail()
    {
        if (Route::input('id') > 0) {
            $table = Table::find(Route::input('id'));
            return View::make('admin.table_edit')->with('table', $table);
        } else {
            return View::make('admin.table_create');
        }
    }

    public function tableCreate()
    {
        $table = new Table();
        $post_data = Input::all();
        $table->fill($post_data);
        $table->save();
        return View::make('admin.table_edit')->with('table', $table)->with('message', 'Added successfully');
    }

    public function access()
    {
        $users = User::all();
        $response = array();
        $response['users'] = $users;
        return View::make('admin.access')->with($response);
    }

    public function accessDetail()
    {
        if (Route::input('id') > 0) {
            $user = User::find(Route::input('id'));
            return View::make('admin.access_edit')->with('user', $user);
        } else {
            return View::make('admin.access_create');
        }
    }

    public function accessEdit()
    {
        $user = User::find(Input::get('id'));
        $user->email = Input::get('email');
        $user->right = Input::get('right');
        $password = Input::get('password');
        if(!empty($password)) {
            $user->password = Hash::make($password);
        }
        $user->save();
        return View::make('admin.access_edit')->with('user', $user)->with('message', 'Edited successfully');
    }

    public function accessCreate()
    {
        $user = new User();
        $user->email = Input::get('email');
        $user->right = Input::get('right');
        $user->password = Hash::make(Input::get('password'));
        $user->save();
        return View::make('admin.access_edit')->with('user', $user)->with('message', 'Added successfully');
    }

    public function accessDelete(){
        $item = User::find(Route::input('id'));
        $item->delete();
    }

    public function category()
    {
        $cats = Category::all();
        $response = array();
        $response['cats'] = $cats;
        return View::make('admin.category')->with($response);
    }

    public function categoryDetail()
    {
        if (Route::input('id') > 0) {
            $cat = Category::find(Route::input('id'));
            return View::make('admin.category_edit')->with('cat', $cat);
        } else {
            return View::make('admin.category_create');
        }
    }

    public function categoryEdit()
    {
        $post_data = Input::all();
        $cat = Category::find(Input::get('id'));
        $cat->fill($post_data);
        $cat->save();
        return View::make('admin.category_edit')->with('cat', $cat)->with('message', 'Edited successfully');
    }

    public function categoryCreate()
    {
        $cat = new Category();
        $post_data = Input::all();
        $cat->fill($post_data);
        $cat->save();
        return View::make('admin.category_edit')->with('cat', $cat)->with('message', 'Added successfully');
    }

    public function item()
    {
        $items = Item::all();
        $response = array();
        $response['items'] = $items;
        return View::make('admin.item')->with($response);
    }

    public function itemDetail()
    {
        $cats = Category::all();
        $catOption = array();
        foreach ($cats as $cat) {
            $catOption[$cat->id] = $cat->catname;
        }
        if (Route::input('id') > 0) {
            $item = Item::find(Route::input('id'));
            return View::make('admin.item_edit')->with('item', $item)->with('catOption', $catOption);
        } else {
            return View::make('admin.item_create')->with('catOption', $catOption);
        }
    }

    public function itemEdit()
    {
        $cats = Category::all();
        $catOption = array();
        foreach ($cats as $cat) {
            $catOption[$cat->id] = $cat->catname;
        }
        $post_data = Input::except('itemimg');
        $item = Item::find(Input::get('id'));
        $item->fill($post_data);
        if (Input::hasFile('itemimg') === true) {
            $destinationPath = public_path() . "/assets/item/";
            Input::file('itemimg')->move($destinationPath, Input::file('itemimg')->getClientOriginalName());
            $item->itemimg = Input::file('itemimg')->getClientOriginalName();
        }
        $item->save();
        return View::make('admin.item_edit')->with('item', $item)->with('message', 'Edited successfully')->with('catOption', $catOption);
    }

    public function itemCreate()
    {
        $cats = Category::all();
        $catOption = array();
        foreach ($cats as $cat) {
            $catOption[$cat->id] = $cat->catname;
        }
        $item = new Item();
        $post_data = Input::except('itemimg');
        $item->fill($post_data);
        if (Input::hasFile('itemimg') === true) {
            $destinationPath = public_path() . "/assets/item/";
            Input::file('itemimg')->move($destinationPath, Input::file('itemimg')->getClientOriginalName());
            $item->itemimg = Input::file('itemimg')->getClientOriginalName();
        }
        $item->save();
        return View::make('admin.item_edit')->with('item', $item)->with('message', 'Added successfully')->with('catOption', $catOption);
    }
}
