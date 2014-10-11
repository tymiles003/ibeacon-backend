<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('item', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('itemname');
			$table->float('price');
			$table->integer('category_id');
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
		Schema::table('item', function(Blueprint $table)
		{
			//
		});
	}

}
