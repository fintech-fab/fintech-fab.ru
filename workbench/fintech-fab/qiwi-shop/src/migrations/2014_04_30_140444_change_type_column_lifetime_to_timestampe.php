<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeTypeColumnLifetimeToTimestampe extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('ff-qiwi-shop')->table('orders', function (Blueprint $table) {
			$table->dropColumn('lifetime');
		});
		Schema::connection('ff-qiwi-shop')->table('orders', function (Blueprint $table) {
			$table->timestamp('lifetime')->after('comment');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('ff-qiwi-shop')->table('orders', function (Blueprint $table) {
			$table->dropColumn('lifetime');
		});
		Schema::connection('ff-qiwi-shop')->table('orders', function (Blueprint $table) {
			$table->string('lifetime')->after('comment');
		});
	}

}
