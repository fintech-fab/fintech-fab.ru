<?php

class RoleTableSeeder extends Seeder
{

	public function run()
	{
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('admin', 'Админ'));
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('moderator', 'Модератор'));
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('user', 'Пользователь'));
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('messageSender', 'Отправитель сообщений'));
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('messageSubscriber', 'Подписчик на получение сообщений'));
	}

}