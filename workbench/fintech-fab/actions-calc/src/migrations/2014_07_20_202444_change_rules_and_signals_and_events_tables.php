<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeRulesAndSignalsAndEventsTables extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('ff-actions-calc')->table('rules', function (Blueprint $table) {
			$table->dropColumn('event_sid');
			$table->dropColumn('signal_sid');
		});

		Schema::connection('ff-actions-calc')->table('rules', function (Blueprint $table) {
			$table->integer('event_id')->after('name');
			$table->integer('signal_id')->after('rule');
		});

		Schema::connection('ff-actions-calc')->table('events', function (Blueprint $table) {
			$table->integer('terminal_id')->after('id');
		});

		Schema::connection('ff-actions-calc')->table('signals', function (Blueprint $table) {
			$table->integer('terminal_id')->after('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('ff-actions-calc')->table('rules', function (Blueprint $table) {
			$table->dropColumn('event_id');
			$table->dropColumn('signal_id');
		});

		Schema::connection('ff-actions-calc')->table('rules', function (Blueprint $table) {
			$table->string('event_sid')->after('name');
			$table->string('signal_sid')->after('rule');
		});

		Schema::connection('ff-actions-calc')->table('events', function (Blueprint $table) {
			$table->dropColumn('terminal_id');
		});
		Schema::connection('ff-actions-calc')->table('signals', function (Blueprint $table) {
			$table->dropColumn('terminal_id');
		});

	}

}
