<?php

class QueueType extends Eloquent{

	protected $table = 'queue_type';
	protected $fillable = array('name', 'capacity', 'disabled');
	
	public function queues()
    {
        return $this->hasMany('queue');
    }
}
