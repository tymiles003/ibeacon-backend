<?php

class Queues extends Eloquent{

	protected $table = 'queue';

	/**
	 * Show the current Queue number.
	 *
	 * @return int
	 */
	public static function currentNumber(){
		$currentNumber = DB::table('queue')
                     ->select(DB::raw('max(id) as id'))
                     ->where('entered', '=', 1)
                     ->first();
        if(!$currentNumber->id){
        	$currentNumber = 0;
        }else{
        	$currentNumber = $currentNumber->id;
        }
        return intval($currentNumber);
	}
}
