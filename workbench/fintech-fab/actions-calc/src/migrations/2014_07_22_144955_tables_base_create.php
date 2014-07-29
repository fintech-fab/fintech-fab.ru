<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablesBaseCreate extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// terminals
		Schema::create('terminals', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('url');
			$table->string('queue');
			$table->string('key');
			$table->string('password');
			$table->timestamps();
		});

		Schema::create('events', function (Blueprint $table) {
			$table->increments('id');
			$table->string('event_sid');
			$table->string('name');
			$table->integer('terminal_id');
			$table->text('data');
			$table->timestamps();
		});

		Schema::create('signals', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('signal_sid');
			$table->integer('terminal_id');
			$table->tinyInteger('flag_url');
			$table->tinyInteger('flag_queue');
			$table->timestamps();
		});

		Schema::create('rules', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->text('rule');
			$table->tinyInteger('flag_active');
			$table->integer('terminal_id');
			$table->integer('event_id');
			$table->integer('signal_id');
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
		Schema::drop('terminals');
		Schema::drop('events');
		Schema::drop('signals');
		Schema::drop('rules');
	}

}
