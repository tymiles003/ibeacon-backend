<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';
	public static $rules = array(
	    'firstname'=>'required|alpha|min:2',
	    'lastname'=>'required|alpha|min:2',
	    'email'=>'required|email|unique:user',
	    'password'=>'required',
	    'password_confirmation'=>'same:password'
    );
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

}
