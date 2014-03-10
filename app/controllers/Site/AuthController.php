<?php
/**
 * @var array $userInfo
 */
namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Auth;
use Config;
use FintechFab\Components\Helper;
use FintechFab\Components\Social;
use FintechFab\Models\User;
use Hash;
use Input;
use Redirect;
use Validator;

class AuthController extends BaseController
{
	public $layout = 'vanguard';
	public function postAuth()
	{
		$email = Input::get('email');
		$password = Input::get('password');

		if (Auth::attempt(array('email' => $email, 'password' => $password))) {
			$title = 'Приветствуем ' . Auth::user()->first_name;

			return Redirect::intended('vanguard')->with('userMessage', 'Вы успешно авторизовались')
				->with('title', $title);
		}

		return Redirect::intended('registration')->with('userMessage', 'Такого пользователя нет.')
			->with('title', 'Ошибка');
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
		$client_id = Config::get('social.ID_vk'); // ID приложения
		$client_secret = Config::get('social.key_vk'); // Защищённый ключ
		$redirect_uri = Config::get('social.url_vk'); // Адрес сайта
		$code = Input::get('code');

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
				'fields' => 'uid,first_name,last_name,bdate,screen_name',
				'access_token' => $token['access_token']
			);

			$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);

			if (isset($userInfo['response'][0]['uid'])) {
				$userInfo = $userInfo['response'][0];
				$result = true;
			}
		}
		if ($result) {

			$userInfo['social_net_name'] = 'vk';
			$userInfo['id'] = $userInfo['uid'];
			$userInfo['link'] = 'https://vk.com/' . $userInfo['screen_name'];
			$user = Social::setSocialUser($userInfo);
			if ($user != null) {
				Auth::login($user);
				$userMessage = "Добро пожаловать на наш сайт!";
				$title = 'Вы успешно авторизовались!';
				$path = 'vanguard';
			}
		} else {
			$userMessage = "Что-то не так, попробуйте ещё раз";
			$title = 'Ошибка';
			$path = 'register';
		}

		return Redirect::to($path)->with('userMessage', $userMessage)->with('title', $title);
	}


	public function fb()
	{
		$params = array(
			'client_id'     => Config::get('social.ID_fb'),
			'client_secret' => Config::get('social.key_fb'),
			'code'          => Input::get('code'),
			'redirect_uri'  => Config::get('social.url_fb')
		);

		$tokenInfo = null;
		parse_str(file_get_contents('https://graph.facebook.com/oauth/access_token' . '?' . http_build_query($params)), $tokenInfo);

		$params = array('access_token' => $tokenInfo['access_token']);
		$userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);

		if (isset($userInfo['id'])) {
			$userInfo['social_net_name'] = 'fb';
			$user = Social::setSocialUser($userInfo);
			if ($user != null) {
				Auth::login($user);
				$userMessage = "Добро пожаловать на наш сайт!";
				$title = 'Вы успешно авторизовались!';
				$path = 'vanguard';
			}
		} else {
			$userMessage = "Что-то не так, попробуйте ещё раз";
			$title = 'Ошибка';
			$path = 'register';
		}

		return Redirect::to($path)->with('userMessage', $userMessage)->with('title', $title);
	}

	public function logout()
	{
		Auth::logout();

		return Redirect::intended('vanguard')->with('userMessage', 'Приходите к нам ещё.')
			->with('title', 'Всего доброго');
	}

}