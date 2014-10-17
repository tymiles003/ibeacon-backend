<?php

class Category extends Eloquent
{

    protected $table = 'category';
    protected $fillable = array('catname');

    public function items()
    {
        return $this->hasMany('Item');
    }
}
