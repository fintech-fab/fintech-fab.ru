<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersBillsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_bills', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('user');
			$table->decimal('amount', 15, 2);
			$table->string('ccy', 3);
			$table->string('comment', 255);
			$table->string('lifetime');
			$table->string('pay_source')->default('qw');
			$table->string('prv_name', 100);
			$table->timestamps();
		});
		Schema::create('qiwi_bills', function (Blueprint $table) {
			$table->increments('id');
			$table->string('user');
			$table->decimal('amount', 15, 2);
			$table->string('ccy', 3);
			$table->string('comment', 255);
			$table->string('lifetime');
			$table->string('pay_source')->default('qw');
			$table->string('prv_name', 100);
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
		Schema::drop('users_bills');
		Schema::drop('qiwi_bills');
	}

}
