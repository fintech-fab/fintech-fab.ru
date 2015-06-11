<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeIndexRoleToTableRole extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('roles', function (Blueprint $table) {
			$table->dropColumn('role');
		});
		Schema::table('roles', function (Blueprint $table) {
			$table->string('role')->after('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('roles', function (Blueprint $table) {
			$table->dropColumn('role');
		});
		Schema::table('roles', function (Blueprint $table) {
			$table->integer('role')->after('id');;
		});
	}

}
