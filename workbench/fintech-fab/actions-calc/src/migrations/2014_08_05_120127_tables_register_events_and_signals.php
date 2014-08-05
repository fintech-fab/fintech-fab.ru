<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablesRegisterEventsAndSignals extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('ff-actions-calc')->create('register_events', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id');
			$table->string('event_sid');
			$table->string('name');
			$table->integer('terminal_id');
			$table->text('data');
			$table->string('result_hash');
			$table->timestamps();
		});

		Schema::connection('ff-actions-calc')->create('register_signals', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('signal_id');
			$table->string('signal_sid');
			$table->integer('terminal_id');
			$table->tinyInteger('flag_url');
			$table->tinyInteger('flag_queue');
			$table->string('result_hash');
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
		Schema::connection('ff-actions-calc')->drop('register_events');
		Schema::connection('ff-actions-calc')->drop('register_signals');
	}

}
