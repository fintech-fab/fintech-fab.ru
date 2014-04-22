<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class QiwiGateRefundTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('qiwiGate')->create('bills_refund', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('bill_id');
			$table->integer('refund_id');
			$table->decimal('amount', 15, 2);
			$table->string('status', 50);
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
		Schema::connection('qiwiGate')->drop('bills_refund');
	}

}
