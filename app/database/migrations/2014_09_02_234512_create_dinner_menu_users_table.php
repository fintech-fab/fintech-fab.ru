<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDinnerMenuUsersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dinner_menu_users', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('dinner_menu_item_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('dinner_menu_item_id')->references('id')->on('dinner_menu_items');
			$table->integer('count')->unsigned()->default(1);
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
		Schema::drop('dinner_menu_users');
	}

}
