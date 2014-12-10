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
        return;
    }


    public function usage()
    {;
        $year = Route::input('year');
        $month = Route::input('month');
        $day = Route::input('day');
        $avg_time = DB::table('queue')
        ->select(DB::raw('COUNT(1) as value, DATE_FORMAT(created_at, "%k") as hour'))
        ->where(DB::raw('YEAR(created_at)'), '=', $year)
        ->where(DB::raw('MONTH(created_at)'), '=', $month)
        ->where(DB::raw('DAY(created_at)'), '=', $day)
        ->where('queue_type_id', '=', Route::input('type'))
        ->groupBy(DB::raw('YEAR(created_at)'))
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->groupBy(DB::raw('DAY(created_at)'))
        ->groupBy(DB::raw('HOUR(created_at)'))
        ->get();
        $i = 0;
        $j = 0;
        $previous_record = array();
        $response = array();
        if(count($avg_time) == 0){
            return Response::json($response);
        }
        for($i = 0; $i < 24; $i++)
        {
            $row['hour'] = $i;
            $row['value'] = 0;
            foreach($avg_time as $record){
                if($record->hour == $i){
                    $row['hour'] = $record->hour+0;
                    $row['value'] = $record->value+0;
                    break;
                }
            }
            array_push($response, $row);
        }
        return Response::json($response);
    }
    public function entranceRate()
    {
        $year = Route::input('year');
        $month = Route::input('month');
        if (isset($year) && $month == 0) {
            $avg_time = DB::table('queue')
            ->select(DB::raw('COUNT(1) as value, DATE_FORMAT(created_at, "%Y-%m") as day'))
            ->where(DB::raw('YEAR(created_at)'), '=', $year)
            ->where('queue_type_id', '=', Route::input('type'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
            $avg_time2 = DB::table('queue')
            ->select(DB::raw('COUNT(1) as value, DATE_FORMAT(created_at, "%Y-%m") as day'))
            ->where(DB::raw('YEAR(created_at)'), '=', $year)
            ->where('queue_type_id', '=', Route::input('type'))
            ->where('entered', '=', 2)
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->groupBy(DB::raw('MONTH(created_at)'))
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
        }
        if (isset($year) && $month > 0) {
            $avg_time = DB::table('queue')
            ->select(DB::raw('COUNT(1) as value, DATE_FORMAT(created_at, "%Y-%m-%d") as day'))
            ->where(DB::raw('YEAR(created_at)'), '=', $year)
            ->where(DB::raw('MONTH(created_at)'), '=', $month)
            ->where('queue_type_id', '=', Route::input('type'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->groupBy(DB::raw('DAY(created_at)'))
            ->get();
            $avg_time2 = DB::table('queue')
            ->select(DB::raw('COUNT(1) as value, DATE_FORMAT(created_at, "%Y-%m-%d") as day'))
            ->where(DB::raw('YEAR(created_at)'), '=', $year)
            ->where(DB::raw('MONTH(created_at)'), '=', $month)
            ->where('queue_type_id', '=', Route::input('type'))
            ->where('entered', '=', 2)
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
        }
        return Response::json($response);
    }


    public function avgWaitingTime()
    {
        $year = Route::input('year');
        $month = Route::input('month');
        if (isset($year) && $month == 0) {
            $avg_time = DB::table('queue')
            ->select(DB::raw('ROUND(AVG(TIME_TO_SEC(TIMEDIFF(updated_at, created_at)))) as value, DATE_FORMAT(created_at, "%Y-%m") as day'))
            ->where(DB::raw('YEAR(created_at)'), '=', $year)
            ->where('entered', '=', 2)
            ->where('queue_type_id', '=', Route::input('type'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        }
        if (isset($year) && $month > 0) {
            $avg_time = DB::table('queue')
            ->select(DB::raw('ROUND(AVG(TIME_TO_SEC(TIMEDIFF(updated_at, created_at)))) as value, DATE_FORMAT(created_at, "%Y-%m-%d") as day'))
            ->where(DB::raw('YEAR(created_at)'), '=', $year)
            ->where(DB::raw('MONTH(created_at)'), '=', $month)
            ->where('entered', '=', 2)
            ->where('queue_type_id', '=', Route::input('type'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->groupBy(DB::raw('DAY(created_at)'))
            ->get();
        }
        return Response::json($avg_time);
    }

    /**
    * Return the average waiting time
    *
    * @return Json
    */
    public function waitingTime()
    {
        $type = Route::input('type');
        if (!isset($type)) {
            $response = array();
            $response['status'] = 'ERROR';
            $response['code']   = 500;
            $response['debug']  = 'Wrong paramters';
            return Response::json($response);
        }
        return Response::json(Queues::waitingTime($type));
    }

    /**
    * Return the current queue number with a json response
    *
    * @return Json
    */
    public function currentNumber()
    {
        $response = array();
        foreach (QueueType::all() as $queueType) {
            $response[$queueType->id] = Queues::currentNumber($queueType->id);
        }
        return Response::json($response);
    }


    /**
    * Return the number of queueing tickets
    *
    * @return Int
    */
    public function totalQueue()
    {
        $type = Route::input('type');
        if (isset($type)) {
            return Queues::totalQueue($type);
        } else {
            $response = array();
            foreach (QueueType::all() as $queueType) {
                $response[$queueType->id] = Queues::totalQueue($queueType->id);
            }
            return Response::json($response);
        }
    }

    /**
    * Return a list of queue (bigger than a number)
    *
    * @return Json
    */
    public function listQueue()
    {
        $type = Route::input('type');
        $id = Route::input('id');
        return Queues::listQueue($type, $id);
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
        $capacity = Input::get('capacity');
        $type = Input::get('type');
        if (!isset($capacity) && !isset($type)) {
            $response = array();
            $response['status'] = 'ERROR';
            $response['code']   = 500;
            $response['debug']  = 'Wrong paramters';
            return Response::json($response);
        }
        //if no capcity is found, suppose only one person
        if(!isset($capacity)){
            $capacity = 1;
        }
        $queue->no_of_people = $capacity;
        //if no type is found, the system determine the type automatically
        if(!isset($type)) {
            $type = QueueType::where('capacity', '>=', $capacity)
            ->orderBy('capacity', 'asc')
            ->first()
            ->id;
        }
        $queue->queue_type_id = $type;
        //check whether queue is open
        if (QueueType::find($type)->disabled == 1){
            $response = array();
            $response['status'] = 'ERROR';
            $response['code']   = 499;
            $response['debug']  = 'The queue is closed';
            return Response::json($response);
        }
        $identifier = Input::get('identifier');
        if (isset($identifier)) {
            $queue->identifier = $identifier;
        }
        $queue->queue_number = DB::table('queue')
        ->where('queue_type_id', '=', $type)
        ->where('cleared', '=', 0)
        ->max('queue_number') + 1;
        $queue->save();

        /**web socket stuff**/
        $entryData = array();
        $entryData['action'] = 'enqueue';
        $entryData['id'] = $queue->id;
        $entryData['type'] = $queue->queue_type_id;
        $entryData['number'] = $queue->queue_number;
        $entryData['people'] = $queue->no_of_people;
        $entryData['created_at'] = $queue->created_at;
        $entryData['totalQueue'] = Queues::totalQueue($queue->queue_type_id);
        $context = new ZMQContext(1, false);
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'new ticket');
        $socket->connect("tcp://127.0.0.1:".Setting::getPPort());
        $socket->send(json_encode($entryData));
        /**end of websocket stuff**/
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
        if(!$queue){
            $response = array();
            $response['status'] = 'ERROR';
            $response['code']   = 498;
            $response['debug']  = 'No such ticket';
            return Response::json($response);
        }
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
        $entered = Input::get('entered');
        if (isset($entered)) { //the user enters a place
            $queue = Queues::find($id);
            $pastEntered = $queue->entered;
            $queue->entered = $entered;
            if($pastEntered != 2){
                $queue->save();
                //websocket message
                if($entered == 2){
                    $entryData = array();
                    $entryData['action']     = 'entered';
                    $entryData['id']         = $queue->id;
                    $entryData['type']       = $queue->queue_type_id;
                    $entryData['totalQueue'] = Queues::totalQueue($queue->queue_type_id);
                    $context = new ZMQContext(1, false);
                    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'entered');
                    $socket->connect("tcp://127.0.0.1:".Setting::getPPort());
                    $socket->send(json_encode($entryData));
                }else{
                    $entryData = array();
                    $entryData['action'] = 'abandon';
                    $entryData['id']     = $queue->id;
                    $entryData['type']   = $queue->queue_type_id;
                    $context = new ZMQContext(1, false);
                    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'abandon');
                    $socket->connect("tcp://127.0.0.1:".Setting::getPPort());
                    $socket->send(json_encode($entryData));
                }
            }
        } else {  //just for dequeue(announcing the number)
            $queue = Queues::find($id);
            $query = DB::table('pushmessage')->where('identifier', '=', $queue->identifier);
            $queue = Queues::find($id);
            $queue->entered = 1;
            $queue->save();

            /**web socket stuff**/
            $entryData = array();
            $entryData['action'] = 'dequeue';
            $entryData['id']     = $queue->id;
            $entryData['type']   = $queue->queue_type_id;
            $context = new ZMQContext(1, false);
            $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'dequeue');
            $socket->connect("tcp://127.0.0.1:".Setting::getPPort());
            $socket->send(json_encode($entryData));
            /**end of websocket stuff**/


            //if display number, then send notification
            if (count($query) > 0) {
                $data = array();
                $data['message']     = Setting::getName().": It is your number";
                $data['identifiers'] = array($queue->identifier);
                $data_string = json_encode($data);
                $this->sendPushNotification($data_string);
            }
            $queues = Queues::where('queue_type_id', '=', $queue->queue_type_id)
            ->where('queue_number', '>', $queue->queue_number)
            ->where('cleared', '=', 0)
            ->where('entered', '=', 0)
            ->take(5)
            ->get();
            $data = array();
            $data['message'] = Setting::getName().": the number is " . $queue->queue_number;
            $data['identifiers'] = array();
            foreach ($queues as $tqueue) {
                array_push($data['identifiers'], $tqueue->identifier);
            }
            $data_string = json_encode($data);
            $this->sendPushNotification($data_string);


            //if display number, then send notification
            $mallURL = Setting::getMallSystem();
            if(!empty($mallURL)){
                $data = array();
                $data['message']     = Setting::getName().": It is your number";
                $data['identifiers'] = array($queue->identifier);
                $data_string = json_encode($data);
                $ch = curl_init($mallURL.'/sendMessage');
                curl_setopt($ch, CURLOPT_USERPWD, Setting::getMallUser().":".Setting::getMallpw());
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );
                curl_exec($ch);

                $data = array();
                $data['message'] = Setting::getName().": the number is " . $queue->queue_number;
                $data['identifiers'] = array();
                foreach ($queues as $tqueue) {
                    array_push($data['identifiers'], $tqueue->identifier);
                }
                $data_string = json_encode($data);
                $ch = curl_init($mallURL.'/sendMessage');
                curl_setopt($ch, CURLOPT_USERPWD, Setting::getMallUser().":".Setting::getMallpw());
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );
                curl_exec($ch);
            }
        }
        return 0;
    }

    public function sendPushNotification($data)
    {
        $data = json_decode($data);
        if (!isset($data->message)) {
            $response = array();
            $response['status'] = 'ERROR';
            $response['code']   = 500;
            $response['debug']  = 'Wrong parameters';
            return Response::json($response);
        }
        $passphrase = '123456';
        $message = $data->message;
        $identifiers = $data->identifiers;
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'qk.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        echo 'Connected to APNS' . PHP_EOL;
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
            );
        $payload = json_encode($body);
        if(count($identifiers) > 0){
            $tokens = DB::table('pushmessage')
            ->whereIn('identifier', $identifiers)->get();
            foreach ($tokens as $token) {
                $msg = chr(0) . pack('n', 32) . pack('H*', $token->token) . pack('n', strlen($payload)) . $payload;
                $result = fwrite($fp, $msg, strlen($msg));
            }
        }
        fclose($fp);
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
