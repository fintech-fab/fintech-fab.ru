<?php

use FintechFab\Models\Role;

class RoleTableSeeder extends Seeder
{

	public function run()
	{
		Role::insert(['role' => 'admin', 'role_name' => 'Админ']);
		Role::insert(['role' => 'moderator', 'role_name' => 'Модератор']);
		Role::insert(['role' => 'user', 'role_name' => 'Пользователь']);
		Role::insert(['role' => 'messageSender', 'role_name' => 'Отправитель сообщений']);
		Role::insert(['role' => 'messageSubscriber', 'role_name' => 'Подписчик на получение сообщений']);
		Role::insert(['role' => 'employee', 'role_name' => 'Сотрудник']);
	}

}