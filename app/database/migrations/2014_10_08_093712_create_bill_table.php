<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bill', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('table_id');
			$table->integer('amount')->nullable();
			$table->integer('member_id')->nullable();
			$table->integer('status');
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
		Schema::table('bill', function(Blueprint $table)
		{
			//
		});
	}

}
