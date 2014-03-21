<?php

class RoleTableSeeder extends Seeder
{

	public function run()
	{
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('admin', 'Админ'));
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('moderator', 'Модератор'));
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('user', 'Пользователь'));
	}

}