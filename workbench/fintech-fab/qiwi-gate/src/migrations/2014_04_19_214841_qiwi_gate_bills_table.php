<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class QiwiGateBillsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('qiwiGate')->create('merchants_bills', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('merchant_id');
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
		Schema::connection('qiwiGate')->drop('merchants_bills');
	}

}
