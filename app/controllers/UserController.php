<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response = array();
		$response['code'] = 200;
		$response['msg'] = "";
		return json_encode($response);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::all(), User::$rules);
		if ($validator->passes()) {
			$user = new User;
			$user->firstname = Input::get('firstname');
	    	$user->lastname = Input::get('lastname');
	    	$user->email = Input::get('email');
	    	$user->password = Hash::make(Input::get('password'));
	    	$user->save();
			$response = array();
			$response['code'] = 200;
			$response['msg'] = "Successful registration";
			return json_encode($response);
	    }else{
			$response = array();
			$response['code'] = 400;
			$response['msg'] = $validator->messages();
			return json_encode($response);
	    }
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

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
		//
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
