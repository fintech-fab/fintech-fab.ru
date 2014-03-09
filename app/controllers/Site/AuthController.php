<?php
/**
 * @var array $userInfo
 */
namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Auth;
use Config;
use FintechFab\Components\Helper;
use FintechFab\Models\User;
use FintechFab\Models\UserVk;
use Hash;
use Input;
use Redirect;
use Validator;

class AuthController extends BaseController
{
	public $layout = 'vanguard';
	public function postAuth()
	{
		$data = Input::all();
		$email = Input::get('email');
		$password = Input::get('password');

		/*echo '<pre>';
		print_r($data);
		echo '</pre>';*/
		//dd($email, $password);
		if (Auth::attempt(array('email' => $email, 'password' => $password))) {
			return Redirect::to('vanguard')->with('userMessage', 'ураааа!!!!');
		}
		if (Auth::check()) {
			dd('урааааа');
		}
		dd('stop');


		return Redirect::to('vanguard')->with('userMessage', $data);

	}

	public function postRegistration()
	{
		$data = Input::all();
		$validator = Validator::make($data, Helper::rulesForInput(), Helper::messagesForErrors());
		$userMessage = $validator->messages()->first();
		$title = 'Ошибка';

		if ($userMessage != null) {
			return Redirect::to('registration')->with('userMessage', $userMessage)
				->with('title', $title)
				->withInput(Input::except('password'));
		}

		$user = new User;

		$user->first_name = Input::get('first_name');
		$user->last_name = Input::get('last_name');
		$user->email = Input::get('email');
		$user->password = Hash::make(Input::get('password'));
		$user->save();

		Auth::login($user);

		$userMessage = "Спасибо за регистрацию";
		$title = 'Регистрация прошла успешно';

		return Redirect::to('vanguard')->with('userMessage', $userMessage)->with('title', $title);

	}

	public function vk()
	{
		$client_id = Config::get('vk.ID_vk'); // ID приложения
		$client_secret = Config::get('vk.key_vk'); // Защищённый ключ
		$redirect_uri = Config::get('vk.url'); // Адрес сайта
		$code = Input::get('code');

		if (isset($code)) {
			$result = false;
			$params = array(
				'client_id'     => $client_id,
				'client_secret' => $client_secret,
				'code'          => $code,
				'redirect_uri'  => $redirect_uri
			);

			$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

			if (isset($token['access_token'])) {
				$params = array(
					'uids'         => $token['user_id'],
					'fields'       => 'uid,first_name,last_name,bdate,',
					'access_token' => $token['access_token']
				);

				$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
				if (isset($userInfo['response'][0]['uid'])) {
					$userInfo = $userInfo['response'][0];
					$result = true;
				}
			}


			if ($result) {
				$id_vk = $userInfo['uid'];
				$first_name = $userInfo['first_name'];
				$last_name = $userInfo['last_name'];
				$bdate = $userInfo['bdate'];
				/*\Session::put('id_vk', $id_vk);
				\Session::put('first_name', $first_name);
				\Session::put('last_name', $last_name);
				\Session::put('bdate', $bdate);
				$this->make('vk');*/


				$user = UserVk::firstOrNew(array(
					'id_vk' => $id_vk,
				));

				$user->setAttribute('id_vk', $id_vk);
				$user->setAttribute('first_name', $first_name);
				$user->setAttribute('last_name', $last_name);
				$user->save();

			}

		}
		$userMessage = "Добро пожаловать на наш сайт!";
		$title = 'Вы успешно авторизовались!';

		return Redirect::to('vanguard')->with('userMessage', $userMessage)->with('title', $title);
	}

	public function logout()
	{
		Auth::logout();
	}

}