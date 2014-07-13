<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPasswordToTerminal extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('ff-actions-calc')->table('terminals', function (Blueprint $table) {
			$table->string('password')->after('queue');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('ff-actions-calc')->table('terminals', function (Blueprint $table) {
			$table->dropColumn('password');
		});
	}

}
