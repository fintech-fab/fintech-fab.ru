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
			//$table->increments('id');
			//$table->integer('number')->unique();
			$table->integer('number')->primary();
			$table->string('html_url', 100);
			$table->string('title', 100);
			$table->string('state', 10);
			$table->dateTime('created');
			$table->dateTime('updated');
			$table->dateTime('closed')->nullable();
			$table->string('user_login', 20);
			$table->foreign('user_login')->references('login')->on('github_members');
		});
		Schema::create('github_comments', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary(); //id из GitHub
			$table->string('html_url', 100);
			$table->integer('issue_number');
			$table->dateTime('created');
			$table->dateTime('updated');
			$table->string('user_login', 20);
			$table->string('prev', 30);
		});
		/**
		 * GET /issues/events
		 *  event:referenced
		 */
		Schema::create('github_refcommits', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary(); //id из GitHub
			$table->string('commit_id', 40);
			$table->string('actor_login', 20);
			$table->dateTime('created');
			$table->integer('issue_number');
			$table->string('message', 256)->default('');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('github_refcommits');
		Schema::dropIfExists('github_comments');
		Schema::dropIfExists('github_issues');
		Schema::drop('github_members');
	}

}
