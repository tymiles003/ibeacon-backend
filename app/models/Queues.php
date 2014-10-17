<?php

class Queues extends Eloquent
{

    protected $table = 'queue';

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
            ->where('entered', '=', 1)
            ->first();
        if (!$currentNumber->number) {
            $currentNumber = 0;
        } else {
            $currentNumber = $currentNumber->number;
        }
        return intval($currentNumber);
    }
}
