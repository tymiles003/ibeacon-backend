<?php

class Item extends Eloquent{

	protected $table = 'item';
	protected $fillable = array('itemname', 'itemimg', 'price', 'category_id');

	public function category()
    {
        return $this->belongsTo('Category');
    }
}
