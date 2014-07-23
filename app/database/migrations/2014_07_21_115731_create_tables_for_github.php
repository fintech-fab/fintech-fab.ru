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
			//$table->increments('id');
			//$table->string('login', 20)->unique();
			$table->string('login', 20)->primary();
			$table->string('avatar_url', 100)->default('');
			$table->integer('contributions')->default(0);
			$table->timestamps();
		});
		Schema::create('github_issues', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('html_url', 100);
			$table->integer('number')->unique();
			$table->string('title', 100);
			$table->string('state', 10);
			$table->dateTime('created');
			$table->dateTime('updated');
			$table->dateTime('closed')->nullable();
			$table->string('userLogin', 20);
			$table->foreign('userLogin')->references('login')->on('github_members');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('github_issues');
		Schema::drop('github_members');
	}

}
