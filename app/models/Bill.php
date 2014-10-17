<?php

class Bill extends Eloquent
{

    protected $table = 'bill';

    public function orders()
    {
        return $this->hasMany('Order');
    }
}
