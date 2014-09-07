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
			$table->text('title')->default(''); //название блюда
			$table->text('description')->default(''); //описание блюда
			$table->decimal('price')->unsigned()->default(0); //цена блюда
			$table->date('date')->default('0000-00-00'); //дата, когда блюдо доступно для заказа
			$table->softDeletes();
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
