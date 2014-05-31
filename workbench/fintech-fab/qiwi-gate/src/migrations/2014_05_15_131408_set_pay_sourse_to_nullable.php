<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class SetPaySourseToNullable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('ff-qiwi-gate')->table('merchants_bills', function (Blueprint $table) {
			$table->dropColumn('pay_source');
		});
		Schema::connection('ff-qiwi-gate')->table('merchants_bills', function (Blueprint $table) {
			$table->string('pay_source')->after('lifetime')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('ff-qiwi-gate')->table('merchants_bills', function (Blueprint $table) {
			$table->dropColumn('pay_source');
		});
		Schema::connection('ff-qiwi-gate')->table('merchants_bills', function (Blueprint $table) {
			$table->string('pay_source')->after('lifetime')->default('qw');;
		});
	}

}
