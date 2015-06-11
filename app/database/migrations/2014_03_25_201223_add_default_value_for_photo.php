<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddDefaultValueForPhoto extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('photo');
		});
		Schema::table('users', function (Blueprint $table) {
			$table->string('photo')->after('password')->default('/img/default_user.png');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('photo');
		});
		Schema::table('users', function (Blueprint $table) {
			$table->string('photo')->after('password');
		});
	}

}
