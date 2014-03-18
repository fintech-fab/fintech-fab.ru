<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPhotoToUserSocialNetwork extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users_social_networks', function (Blueprint $table) {
			$table->string('photo')->after('link');
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
	}

}
