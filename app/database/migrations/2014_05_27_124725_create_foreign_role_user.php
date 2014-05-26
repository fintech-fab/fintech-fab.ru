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
		Schema::table('role_user', function(Blueprint $table)
		{

			Schema::table('role_user', function (Blueprint $table) {
				$table->renameColumn('user_id', 'user_id0');
				$table->renameColumn('role_id', 'role_id0');
			});
			Schema::table('role_user', function (Blueprint $table) {
				$table->integer('user_id')->unsigned();
				$table->integer('role_id')->unsigned();
			});
			DB::update("UPDATE  `role_user` SET  `user_id` =  `user_id0`,  `role_id`= `role_id0`");
			Schema::table('role_user', function (Blueprint $table) {
				$table->dropColumn('user_id0');
				$table->dropColumn('role_id0');
			});

			Schema::table('role_user', function (Blueprint $table) {
				$table->foreign('user_id')->references('id')->on('users');
				$table->foreign('role_id')->references('id')->on('roles');
			});
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

		Schema::table('role_user', function (Blueprint $table) {
			$table->renameColumn('user_id', 'user_id0');
			$table->renameColumn('role_id', 'role_id0');
		});
		Schema::table('role_user', function (Blueprint $table) {
			$table->integer('user_id');
			$table->integer('role_id');
		});
		DB::update("UPDATE  `role_user` SET  `user_id` =  `user_id0`,  `role_id`= `role_id0`");
		Schema::table('role_user', function (Blueprint $table) {
			$table->dropColumn('user_id0');
			$table->dropColumn('role_id0');
		});

	}

}
