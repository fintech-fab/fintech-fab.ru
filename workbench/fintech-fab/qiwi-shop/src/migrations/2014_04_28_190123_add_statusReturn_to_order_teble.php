<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddStatusReturnToOrderTeble extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('qiwiShop')->table('orders', function (Blueprint $table) {
			$table->string('statusReturn')->nullable()->after('status')->default(null);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('qiwiShop')->table('orders', function (Blueprint $table) {
			$table->dropColumn('statusReturn');
		});
	}

}
