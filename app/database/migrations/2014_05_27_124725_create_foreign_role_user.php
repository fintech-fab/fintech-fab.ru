<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignRoleUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE " . DB::getTablePrefix() . "role_user
			CHANGE user_id user_id INT(10) UNSIGNED NOT NULL,
			CHANGE role_id role_id INT(10) UNSIGNED NOT NULL");

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
		Schema::table('role_user', function (Blueprint $table) {
			$table->dropForeign('role_user_user_id_foreign');
			$table->dropForeign('role_user_role_id_foreign');
		});

		DB::statement("ALTER TABLE " . DB::getTablePrefix() . "role_user
			CHANGE user_id user_id INT(11) NOT NULL,
			CHANGE role_id role_id INT(11) NOT NULL");
	}

}
