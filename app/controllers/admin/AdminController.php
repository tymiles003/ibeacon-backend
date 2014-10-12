<?php

class AdminController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function queue(){
		$currentNumber = Queues::currentNumber();
		$queues = Queues::where('id', '>', $currentNumber)->get()->sortBy('id');
		$maxQueueNumber = DB::table('queue')->max('id');
		$response = array();
		$response['queues'] = $queues;
		$response['maxQueueNumber'] = $maxQueueNumber;
		return View::make('admin.queue')->with($response);
	}

	
	public function category(){
		$cats = Category::all();
		$response = array();
		$response['cats'] = $cats;
		return View::make('admin.category')->with($response);
	}

	public function categoryDetail(){
		if(Route::input('id') > 0){
			$cat = Category::find(Route::input('id'));
			return View::make('admin.category_edit')->with('cat', $cat);
		}else{
			return View::make('admin.category_create');
		}
	}

	public function categoryEdit(){
		$post_data = Input::all();
		$cat = Category::find(Input::get('id'));
		$cat->fill($post_data);
		$cat->save();
		return View::make('admin.category_edit')->with('cat', $cat)->with('message', 'Edit successfully');
	}

	public function categoryCreate(){
		$cat = new Category();
		$post_data = Input::all();
		$cat->fill($post_data);
		$cat->save();
		return View::make('admin.category_edit')->with('cat', $cat)->with('message', 'Add successfully');
	}

	public function item(){
		$items = Item::all();
		$response = array();
		$response['items'] = $items;
		return View::make('admin.item')->with($response);
	}

	public function itemDetail(){
		$cats = Category::all();
		$catOption = array();
		foreach($cats as $cat){
			$catOption[$cat->id] = $cat->catname;
		}
		if(Route::input('id') > 0){
			$item = Item::find(Route::input('id'));
			return View::make('admin.item_edit')->with('item', $item)->with('catOption', $catOption);
		}else{
			return View::make('admin.item_create')->with('catOption', $catOption);
		}
	}

	public function itemEdit(){
		$cats = Category::all();
		$catOption = array();
		foreach($cats as $cat){
			$catOption[$cat->id] = $cat->catname;
		}
		$post_data = Input::except('itemimg');
		$item = Item::find(Input::get('id'));
		$item->fill($post_data);
		if (Input::hasFile('itemimg') === true)
		{
			$destinationPath = public_path()."/assets/item/";
		    Input::file('itemimg')->move($destinationPath, Input::file('itemimg')->getClientOriginalName());
		    $item->itemimg = Input::file('itemimg')->getClientOriginalName();
		}
		$item->save();
		return View::make('admin.item_edit')->with('item', $item)->with('message', 'Edit successfully')->with('catOption', $catOption);
	}

	public function itemCreate(){
		$cats = Category::all();
		$catOption = array();
		foreach($cats as $cat){
			$catOption[$cat->id] = $cat->catname;
		}
		$item = new Item();
		$post_data = Input::except('itemimg');
		$item->fill($post_data);
		if (Input::hasFile('itemimg') === true)
		{
			$destinationPath = public_path()."/assets/item/";
		    Input::file('itemimg')->move($destinationPath, Input::file('itemimg')->getClientOriginalName());
		    $item->itemimg = Input::file('itemimg')->getClientOriginalName();
		}
		$item->save();
		return View::make('admin.item_edit')->with('item', $item)->with('message', 'Add successfully')->with('catOption', $catOption);
	}
}
