<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDinnerMenuUsersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dinner_menu_users', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('dinner_menu_item_id')->unsigned();
			// в данном случае - внешние ключи - не сильно необходимы, потому что
			// удалять пользователей и пункты меню обедов - не планируется
			// удалять вообще плохо для индексов (проще просто отдельным полем "выключить")
			// т.е. не плохо - что они (внешние ключи) есть. но толку никакого.
			// если будете с базой работать вручную, или ее в юнит-тестах - будет мучение сплошное.
			// например вы не сможете создать специально ошибочную операцию для тестирования вашего кода.
			// лучше если целостность контролируется не базой, а вами собственноручно.
			// т.е. у вас в коде не происходит неконтролируемых ситуаций, которые нужно защищать внешними ключами.
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('dinner_menu_item_id')->references('id')->on('dinner_menu_items');
			$table->integer('count')->unsigned()->default(1);
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
		Schema::drop('dinner_menu_users');
	}

}
