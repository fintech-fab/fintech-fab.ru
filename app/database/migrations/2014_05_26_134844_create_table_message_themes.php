<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableMessageThemes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('message_themes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('theme', 64);
			$table->string('name', 32);
			$table->string('message', 255)->default('');
			$table->string('comment', 100)->default('');
			$table->timestamps();
		});
	}

	/**
	 *
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('message_themes');
	}

}
