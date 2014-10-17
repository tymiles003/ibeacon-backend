<?php

class Beacon extends Eloquent
{

    protected $table = 'beacon';
    protected $fillable = array('major', 'minor', 'table_id');

    public function beacons()
    {
        return $this->belongsTo('Table');
    }
}
