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
		// всегда нужно делать значения по умолчанию для полей
		// если это строка - то пустое
		// если число - то какое то осмысленное значение
		// если дата-время - тоже заполнять (нулями, если нет)
		// NULL можно использовать только для текстовых полей (TEXT)

		// причины так делать:
		// во первых - чтобы не думать лишний раз - надо/ненадо (не думаем и всегда делаем значения по умолчанию)
		// во вторых, в mysql есть небольшая неприятность с индексами на полях, которые могут быть NULL
		// в-третьих, это для дисциплины - всегда задумывайтесь над тем, что будет в этом поле
		// в-четвертых, так вы избегаете ошибок (можно не передавать значение из php при сохранении)
		Schema::create('dinner_menu_items', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title'); //название блюда
			// string не больше 255 размером
			// для текстовых есть $table->text()
			$table->string('description', 512)->nullable(); //описание блюда
			$table->decimal('price', 7, 2)->unsigned(); //цена блюда
			$table->date('date'); //дата, когда блюдо доступно для заказа
			$table->timestamps();
		});

		// те же самые коменты - ко второй миграции
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
