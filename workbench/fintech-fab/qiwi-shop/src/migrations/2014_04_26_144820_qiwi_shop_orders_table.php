<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class QiwiShopOrdersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('qiwiShop')->create('orders', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('item');
			$table->decimal('sum', 15, 2);
			$table->string('tel');
			$table->string('comment');
			$table->string('lifetime');
			$table->string('status');
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
		Schema::connection('qiwiShop')->drop('orders');
	}

}
