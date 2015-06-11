<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthTables extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('first_name', 32)->nullable();
			$table->string('last_name', 32)->nullable();
			$table->string('email')->nullable()->unique();
			$table->string('password');
			$table->timestamps();
		});
		Schema::create('users_social_networks', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('id_user_in_network');
			$table->string('social_net_name');
			$table->string('first_name', 32);
			$table->string('last_name', 32);
			$table->string('link');
			$table->timestamps();
		});
		Schema::create('roles', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('role');
			$table->string('role_name', 32);
			$table->timestamps();
		});
		Schema::create('users_roles', function (Blueprint $table) {
			$table->integer('user_id')->unique();
			$table->integer('role_id')->unique();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
		Schema::drop('users_social_networks');
		Schema::drop('roles');
		Schema::drop('users_roles');
	}

}
