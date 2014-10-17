<?php

class Order extends Eloquent{

	protected $table = 'order';
	public function bill()
    {
        return $this->belongsTo('Bill');
    }
    public function item()
    {
        return $this->belongsTo('Item');
    }
}
