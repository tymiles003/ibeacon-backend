<?php

class QueueTypeController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $queueTypes = QueueType::where('disabled', '=', 0)
            ->orderBy('capacity', 'ASC')->get();
        foreach($queueTypes as $queueType){
            $queueType->count = Queues::totalQueue($queueType->id);
			$queueType->currentno = Queues::currentNumber($queueType->id);
        }
        return Response::json($queueTypes);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $queueType = QueueType::find($id);
		if(!$queueType){
			$response = array();
			$response['status'] = 'ERROR';
			$response['code'] = 500;
			$response['debug'] = 'No such queue type';
			return Response::json($response);
		}
		$queueType->currentno = Queues::currentNumber($id);
		$queueType->count = Queues::totalQueue($id);
        return Response::json($queueType);
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
		$queueType = QueueType::find($id);
        $queueType->delete();
	}


}
