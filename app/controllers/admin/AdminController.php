<?php

class AdminController extends \BaseController
{
    public function setting()
    {
        return View::make('admin.setting');
    }

    public function updateSetting()
    {
        foreach (Input::except('_token') as $key=>$value){
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
            return Redirect::to('admin/queue');
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
