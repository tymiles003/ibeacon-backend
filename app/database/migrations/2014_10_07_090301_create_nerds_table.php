<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNerdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('queue', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('queue_number');
			$table->integer('member_id')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('queue', function(Blueprint $table)
		{
			//
		});
	}

}
