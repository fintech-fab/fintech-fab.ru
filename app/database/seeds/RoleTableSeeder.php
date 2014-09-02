<?php

class RoleTableSeeder extends Seeder
{

	public function run()
	{
		$sql = 'insert into ' . DB::getTablePrefix() . 'roles (role, role_name) values (?, ?)';
		DB::insert($sql, array('admin', 'Админ'));
		DB::insert($sql, array('moderator', 'Модератор'));
		DB::insert($sql, array('user', 'Пользователь'));
		DB::insert($sql, array('messageSender', 'Отправитель сообщений'));
		DB::insert($sql, array('messageSubscriber', 'Подписчик на получение сообщений'));
        DB::insert($sql, array('employee', 'Сотрудник'));
    }

}