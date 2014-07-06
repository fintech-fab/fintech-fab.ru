<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeColumnSignalSidInRules extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('ff-actions-calc')->table('rules', function (Blueprint $table) {
			$table->dropColumn('signal_sid');
		});

		Schema::connection('ff-actions-calc')->table('rules', function (Blueprint $table) {
			$table->string('signal_sid')->after('rule');
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
			$table->dropColumn('signal_sid');
		});

		Schema::connection('ff-actions-calc')->table('rules', function (Blueprint $table) {
			$table->string('signal_sid');
		});
	}

}
