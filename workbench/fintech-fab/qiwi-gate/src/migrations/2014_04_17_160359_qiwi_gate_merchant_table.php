<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class QiwiGateMerchantTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('qiwiGate')->create('merchants', function (Blueprint $table) {
			$table->increments('id');
			$table->string('username', 32);
			$table->string('password', 32);
			$table->string('callback_url');
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
		Schema::connection('qiwiGate')->drop('merchants');
	}

}
