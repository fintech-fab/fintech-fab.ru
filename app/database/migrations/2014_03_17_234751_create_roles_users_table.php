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
		Schema::drop('role_user');

	}

}
