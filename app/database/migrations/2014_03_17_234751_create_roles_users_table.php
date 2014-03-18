<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolesUsersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('users_roles');
		Schema::create('role_user', function (Blueprint $table) {
			$table->integer('user_id');
			$table->integer('role_id');
		});
		Schema::table('users', function (Blueprint $table) {
			$table->string('photo')->after('password');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users_social_networks', function (Blueprint $table) {
			$table->dropColumn('photo');
		});
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('photo');
		});
		Schema::drop('role_user');
	}

}
