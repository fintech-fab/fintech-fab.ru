<?php

class RoleTableSeeder extends Seeder
{

	public function run()
	{
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('1', 'admin'));
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('2', 'moderator'));
		DB::insert('insert into roles (role, role_name) values (?, ?)', array('3', 'user'));
	}

}