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
			//$table->integer('user_id')->unique();
			//$table->integer('role_id')->unique();
			$table->integer('user_id')->unsigned();
			$table->integer('role_id')->unsigned();
		});
		Schema::table('role_user', function (Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('role_id')->references('id')->on('roles');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('users_roles', function (Blueprint $table) {
			$table->integer('user_id')->unique();
			$table->integer('role_id')->unique();
		});

		Schema::table('role_user', function (Blueprint $table) {
			$table->dropForeign('role_user_user_id_foreign');
			$table->dropForeign('role_user_role_id_foreign');
		});

		Schema::drop('role_user');

	}

}
