<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablesForActionsCalc extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('ff-actions-calc')->create('terminals', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('url');
			$table->string('queue');
			$table->timestamps();
		});

		Schema::connection('ff-actions-calc')->create('events', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('terminal_id');
			$table->string('sid');
			$table->string('data');
			$table->timestamps();
		});

		Schema::connection('ff-actions-calc')->create('rules', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('terminal_id');
			$table->string('name');
			$table->string('event_sid');
			$table->string('rule');
			$table->string('signal');
			$table->boolean('flag_active');
			$table->timestamps();
		});

		Schema::connection('ff-actions-calc')->create('signals', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id');
			$table->string('name');
			$table->string('signal_sid');
			$table->boolean('flag_url');
			$table->boolean('flag_queue');
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
		Schema::connection('ff-actions-calc')->drop('terminals');
		Schema::connection('ff-actions-calc')->drop('events');
		Schema::connection('ff-actions-calc')->drop('rules');
		Schema::connection('ff-actions-calc')->drop('signals');
	}

}
