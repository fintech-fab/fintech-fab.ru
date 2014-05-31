<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class QiwiShopPayReturnTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('ff-qiwi-shop')->create('orders_pay_return', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id');
			$table->decimal('sum', 15, 2);
			$table->string('comment');
			$table->string('status');
			$table->timestamps();
		});

		Schema::connection('ff-qiwi-shop')->table('orders', function (Blueprint $table) {
			$table->dropColumn('statusReturn');
		});
		Schema::connection('ff-qiwi-shop')->table('orders', function (Blueprint $table) {
			$table->string('idLastReturn')->nullable()->after('status')->default(null);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('ff-qiwi-shop')->drop('orders_pay_return');
		Schema::connection('ff-qiwi-shop')->table('orders', function (Blueprint $table) {
			$table->string('statusReturn')->nullable()->after('status')->default(null);
		});
		Schema::connection('ff-qiwi-shop')->table('orders', function (Blueprint $table) {
			$table->dropColumn('idLastReturn');
		});
	}

}
