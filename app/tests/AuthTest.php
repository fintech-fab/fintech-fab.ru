<?php
use FintechFab\Models\User;
use Zizaco\FactoryMuff\Facade\FactoryMuff;

class AuthTest extends TestCase
{

	public function testBackRedirectAfterRegistration()
	{
		User::truncate();

		$this->call('GET', '/vanguard');

		$this->call(
			'POST',
			'/registration',
			array(
				'first_name' => 'Вася',
				'last_name'  => 'Пупкин',
				'email'      => 'vasya@example.com',
				'password'   => '123123',
			)
		);

		$this->assertEquals(1, User::count());
		$user = User::find(1);
		$this->assertEquals('Вася', $user->first_name);
		$this->assertRedirectedTo('/vanguard');

	}

	public function testAuth()
	{
		/**
		 * @var User $user
		 */

		$user = FactoryMuff::create(User::class);
		$user->password = Hash::make('qwerty');
		$user->save();

		$this->call('GET', '/vanguard');
		$resp = $this->call(
			'POST',
			'/auth',
			array(
				'email'    => $user->email,
				'password' => 'qwerty',
			)
		);

		$this->assertNotEmpty($resp->original['authOk']);
		$this->assertContains('Выход', $resp->original['authOk']);

	}

	public function testFailAuth()
	{
		/**
		 * @var User $user
		 */

		$user = FactoryMuff::create(User::class);
		$user->password = Hash::make('qwerty');
		$user->save();

		$this->call('GET', '/vanguard');
		$resp = $this->call(
			'POST',
			'/auth',
			array(
				'email'    => $user->email,
				'password' => 'qwerty1',
			)
		);

		$this->assertNotEmpty($resp->original['errors']);
		$this->assertEquals('Нет такого пользователя', $resp->original['errors'][1]);
		$this->assertArrayNotHasKey('authOk', $resp->original);

	}


}