<?php

class QueueController extends \BaseController {
	public function __construct()
    {
        // Exit if it is not admin
        //$this->beforeFilter('admin', array('only' => 'index'));
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}

	/**
	 * Return the current queue number with a json response
	 *
	 * @return Json
	 */
	public function currentNumber(){
		$response = array();
		$response['id'] = Queues::currentNumber();
		return Response::json($response);
	}

	/**
	 * Return a list of queue (bigger than a number)
	 *
	 * @return Json
	 */
	public static function listQueue(){
		$id = Route::input('id');
		if (isset($id)){
			$queues = Queues::where('id', '>', $id)->get()->sortBy('id');
			return $queues;
		}else{
			$currentNumber = Queues::currentNumber();
			$queues = Queues::where('id', '>', $currentNumber)->get()->sortBy('id');
			return $queues;
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('hello');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$queue = new Queues;
		$queue->lastname = Input::get('lastname');
		$queue->save();
		return $queue->toJson();
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$queue = Queues::find($id);
		return $queue->toJson();
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$queue = Queues::find($id);
		$queue->entered = 1;
		$queue->save();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
