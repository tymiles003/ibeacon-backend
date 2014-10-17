<?php

class QueueController extends \BaseController
{
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
        //
    }

    public function entranceRate()
    {
        $avg_time = DB::table('queue')
            ->select(DB::raw('COUNT(1) as value, DATE_FORMAT(created_at, "%Y-%m-%d") as day'))
            ->where('queue_type_id', '=', Route::input('type'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->groupBy(DB::raw('DAY(created_at)'))
            ->get();
        $avg_time2 = DB::table('queue')
            ->select(DB::raw('COUNT(1) as value, DATE_FORMAT(created_at, "%Y-%m-%d") as day'))
            ->where('queue_type_id', '=', Route::input('type'))
            ->where('entered', '=', 1)
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->groupBy(DB::raw('DAY(created_at)'))
            ->get();
        $i = 0;
        $response = array();
        foreach ($avg_time as $record) {
            if ($i >= count($avg_time2)) {
                break;
            }
            $row = array();
            $row['day'] = $record->day;
            if ($record->day == $avg_time2[$i]->day) {
                $row['value'] = intval($avg_time2[$i]->value / $record->value * 100);
                $i++;
            } else {
                $row['value'] = 0;
            }
            array_push($response, $row);
        }
        return Response::json($response);
    }


    public function avgWaitingTime()
    {
        $avg_time = DB::table('queue')
            ->select(DB::raw('ROUND(AVG(TIME_TO_SEC(TIMEDIFF(updated_at, created_at)))) as value, DATE_FORMAT(created_at, "%Y-%m-%d") as day'))
            ->where('entered', '=', 1)->where('queue_type_id', '=', Route::input('type'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->groupBy(DB::raw('DAY(created_at)'))
            ->get();
        return $avg_time;
    }

    /**
     * Return the average waiting time
     *
     * @return Json
     */
    public function waitingTime()
    {
        $type = Route::input('type');
        $avg_time = DB::table('queue')->select(DB::raw('AVG(TIME_TO_SEC(TIMEDIFF(updated_at, created_at))) as diff, queue_type_id'))->where('entered', '=', 1)->where('queue_type_id', '=', $type)->groupBy('queue_type_id')->get();
        //$avg_time[0]
        $response = array();
        if (isset($avg_time[0])) {
            $response['time'] = intval($avg_time[0]->diff);
        } else {
            $response['time'] = 0;
        }
        return Response::json($response);
    }

    /**
     * Return the current queue number with a json response
     *
     * @return Json
     */
    public function currentNumber()
    {
        $count = QueueType::count();
        $response = array();
        for ($i = 1; $i <= $count; $i++) {
            $response[$i] = Queues::currentNumber($i);
        }
        return Response::json($response);
    }


    /**
     * Return a list of queue (bigger than a number)
     *
     * @return Json
     */
    public static function listQueue()
    {
        $type = Route::input('type');
        $number = Route::input('id');
        if (isset($number)) {
            $queues = Queues::where('queue_type_id', '=', $type)->where('queue_number', '>', $number)->get()->sortBy('queue_number');
            return $queues;
        } else {
            $currentNumber = Queues::currentNumber($type);
            $queues = Queues::where('queue_type_id', '=', $type)->where('queue_number', '>', $currentNumber)->get()->sortBy('queue_number');
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
        //determine the queue type
        $type = QueueType::where('capacity', '>=', Input::get('capacity'))->orderBy('capacity', 'asc')->first()->id;
        $queue->queue_type_id = $type;
        $queue->queue_number = DB::table('queue')->where('queue_type_id', '=', $type)->max('queue_number') + 1;
        $queue->save();
        return $queue->toJson();
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
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
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
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
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}
