<?php

class Queues extends Eloquent
{

    protected $table = 'queue';


    public static function listQueue($type, $id){
        if (isset($id)) {
            $queues = Queues::where('queue_type_id', '=', $type)
                ->where('cleared', '=', 0)
                ->where('queue_number', '>', $id)
                ->get()
                ->sortBy('queue_number');
            return $queues;
        } else {
            $currentNumber = Queues::currentNumber($type);
            $queues = Queues::where('queue_type_id', '=', $type)
                ->where('cleared', '=', 0)
                ->where('queue_number', '>', $currentNumber)
                ->get()
                ->sortBy('queue_number');
            return $queues;
        }
    }

    public static function waitingTime($type){
        $avg_time = DB::table('queue')
            ->select(DB::raw('AVG(TIME_TO_SEC(TIMEDIFF(updated_at, created_at))) as diff, queue_type_id'))
            ->where('entered', '=', 2)->where('queue_type_id', '=', $type)
            ->groupBy('queue_type_id')->get();
        //$avg_time[0]
        $response = array();
        if (isset($avg_time[0])) {
            $response['time'] = intval($avg_time[0]->diff);
        } else {
            $response['time'] = 0;
        }
        return $response;
    }

    /**
     * Show the total tickets queueing.
     *
     * @return int
     */
    public static function totalQueue($type){
        $currentNumber = Queues::currentNumber($type);
        $total = DB::table('queue')
            ->select(DB::raw('COUNT(1) as sum'))
            ->where('queue_number', '>', $currentNumber)
            ->where('queue_type_id', '=', $type)
            ->where('cleared', '=', 0)
            ->where('entered', '=', 0)
            ->first();
        return $total->sum;
    }
    /**
     * Show the current Queue number.
     *
     * @return int
     */
    public static function currentNumber($type = 0)
    {
        $currentNumber = DB::table('queue')
            ->select(DB::raw('max(queue_number) as number'))
            ->where('queue_type_id', '=', $type)
            ->where('cleared', '=', 0)
            ->where('entered', '>', 0)
            ->where('entered', '<', 3)
            ->first();
        if (!$currentNumber->number) {
            $currentNumber = 0;
        } else {
            $currentNumber = $currentNumber->number;
        }
        return intval($currentNumber);
    }
}
