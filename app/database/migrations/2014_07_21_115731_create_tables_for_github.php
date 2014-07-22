<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesForGithub extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('github_members', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('login', 32)->unique();
			$table->string('avatar_url', 100)->nullable();
			$table->integer('contributions')->default(0);
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
		Schema::drop('github_members');
	}

}
