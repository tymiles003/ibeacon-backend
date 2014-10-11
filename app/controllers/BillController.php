<?php

class BillController extends \BaseController {

	public function getBillByTableID(){
		$bill = Bill::where('table_id', '=', Route::input('id'))->get();
		
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
