<?php

class QueueType extends Eloquent{

	protected $table = 'queue_type';
	protected $fillable = array('name', 'capacity');
	
	public function queues()
    {
        return $this->hasMany('queue');
    }
}
