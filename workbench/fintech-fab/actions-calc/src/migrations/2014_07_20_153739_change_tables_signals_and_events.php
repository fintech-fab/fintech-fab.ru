<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeTablesSignalsAndEvents extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('ff-actions-calc')->drop('signals');
		Schema::connection('ff-actions-calc')->drop('events');

		Schema::connection('ff-actions-calc')->create('result_signals', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id');
			$table->string('signal_sid');
			$table->boolean('flag_url');
			$table->boolean('flag_queue');
			$table->timestamps();
		});

		Schema::connection('ff-actions-calc')->create('signals', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('signal_sid');
			$table->timestamps();
		});

		Schema::connection('ff-actions-calc')->create('income_events', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('terminal_id');
			$table->string('sid');
			$table->string('data');
			$table->timestamps();
		});

		Schema::connection('ff-actions-calc')->create('events', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('event_sid');
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
		Schema::connection('ff-actions-calc')->drop('signals');
		Schema::connection('ff-actions-calc')->drop('events');
		Schema::connection('ff-actions-calc')->drop('result_signals');
		Schema::connection('ff-actions-calc')->drop('income_events');

		Schema::connection('ff-actions-calc')->create('signals', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id');
			$table->string('signal_sid');
			$table->boolean('flag_url');
			$table->boolean('flag_queue');
			$table->timestamps();
		});

		Schema::connection('ff-actions-calc')->create('events', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('terminal_id');
			$table->string('sid');
			$table->string('data');
			$table->timestamps();
		});

	}

}
