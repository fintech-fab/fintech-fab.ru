<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDinnerMenuItemsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dinner_menu_items', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title'); //название блюда
			$table->string('description', 512)->nullable(); //описание блюда
			$table->decimal('price', 7, 2)->unsigned(); //цена блюда
			$table->date('date'); //дата, когда блюдо доступно для заказа
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
		Schema::drop('dinner_menu_items');
	}

}
