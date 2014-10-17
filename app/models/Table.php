<?php

class Table extends Eloquent{

	protected $table = 'table';
	protected $fillable = array('tableno', 'capacity');

	public function beacons()
    {
        return $this->hasMany('Beacon');
    }
}
