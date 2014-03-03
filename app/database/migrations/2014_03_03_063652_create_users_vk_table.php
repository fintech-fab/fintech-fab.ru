<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersVkTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("users_vk", function ($table) {
			$table->increments("id");
			$table->integer("id_vk");
			$table->string("first_name", 32);
			$table->string("last_name", 32);
			$table->integer("id_role");
			$table->integer("link_vk");
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
		//
	}

}
