<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSectionIdToDinnerMenuItems extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dinner_menu_items', function (Blueprint $table) {
			$table->integer('section_id')->default(0); //id раздела
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dinner_menu_items', function (Blueprint $table) {
			$table->dropColumn('section_id');
		});
	}

}
