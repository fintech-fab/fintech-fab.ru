<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddBillIdToMerchantBiilsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('qiwiGate')->table('merchants_bills', function (Blueprint $table) {
			$table->string('bill_id', 100)->after('merchant_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('qiwiGate')->table('merchants_bills', function (Blueprint $table) {
			$table->dropColumn('bill_id');
		});
	}

}
