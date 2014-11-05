<?php

class BillController extends \BaseController {

	public function getBillByTableID(){
		$bill = Bill::where('table_id', '=', Route::input('id'))->where('status', '=', 0)->get();
		//if a bill is not found, create a new one
		if(count($bill) == 0){
			$bill = new Bill();
			$bill->table_id = Route::input('id');
			$bill->save();
			return $bill;
		}else{
			$bill = $bill->first();
			return $bill;
		}
	}
}
