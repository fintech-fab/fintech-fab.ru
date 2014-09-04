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
			$table->string('title')->default(''); //название блюда
			$table->text('description')->nullable(); //описание блюда
			$table->decimal('price', 7, 2)->unsigned()->default(0); //цена блюда
			$table->date('date')->default('0000-00-00'); //дата, когда блюдо доступно для заказа
			$table->boolean('deleted')->default(false); //вместо удаления нужно сделать это поле true
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
