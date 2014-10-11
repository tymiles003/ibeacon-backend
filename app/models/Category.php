<?php

class Category extends Eloquent{

	protected $table = 'category';

	public function items()
    {
        return $this->hasMany('Item');
    }
}
