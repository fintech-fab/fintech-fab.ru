<?php
/**
 * @var array $userInfo
 */
namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Auth;
use Config;
use FintechFab\Components\Social;
use FintechFab\Components\WorkWithInput;
use FintechFab\Models\User;
use FintechFab\Widgets\LinksInMenu;
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
		$remember = isset($data['remember']) ? true : false;

		$validator = Validator::make($data, WorkWithInput::rulesForInputAuth(), WorkWithInput::messagesForErrors());
		$userMessages = $validator->messages();
		$emailError = $userMessages->first('email');
		$emailPassword = $userMessages->first('password');
		$result['errors'] = array($emailError, $emailPassword);

		if ($userMessages->has('email') || $userMessages->has('password')) {
			return $result;
		}

		if (Auth::attempt(array('email' => $data['email'], 'password' => $data['password']), $remember)) {
			$result['authOk'] = LinksInMenu::echoAuthMode();
			return $result;
		}
		$result['errors']['1'] = "Нет такого пользователя";

		return $result;
	}

	public function postRegistration()
	{
		$data = Input::all();
		$validator = Validator::make($data, WorkWithInput::rulesForInputRegistration(), WorkWithInput::messagesForErrors());
		$userMessage = $validator->messages()->first();
		$title = 'Ошибка';

		if ($userMessage != null) {
			return Redirect::to('registration')
				->with('userMessage', $userMessage)
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

		return Redirect::back()->with('userMessage', $userMessage)->with('title', $title);

	}

	public function vk()
	{
		$result = false;
		$params = array(
			'client_id'     => Config::get('social.ID_vk'),
			'client_secret' => Config::get('social.key_vk'),
			'code'          => Input::get('code'),
			'redirect_uri'  => Config::get('social.url_vk')
		);

		$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?'
			. urldecode(http_build_query($params))), true);

		if (isset($token['access_token'])) {
			$params = array(
				'uids'         => $token['user_id'],
				'fields' => 'uid,first_name,last_name,bdate,screen_name,photo_big',
				'access_token' => $token['access_token']
			);

			$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?'
				. urldecode(http_build_query($params))), true);

			if (isset($userInfo['response'][0]['uid'])) {
				$userInfo = $userInfo['response'][0];
				$result = true;
			}
		}
		if (!$result) {
			$this->resultError();
		}
		$userInfo['social_net_name'] = 'vk';
		$userInfo['id'] = $userInfo['uid'];
		$userInfo['photo'] = $userInfo['photo_big'];
		$userInfo['link'] = 'https://vk.com/' . $userInfo['screen_name'];
		$user = Social::setSocialUser($userInfo);

		if (is_null($user)) {
			$this->resultError();
		}

		return Redirect::back()
			->with('userMessage', 'Добро пожаловать на наш сайт!')
			->with('title', 'Вы успешно авторизовались!');
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
		parse_str(file_get_contents('https://graph.facebook.com/oauth/access_token' . '?'
			. http_build_query($params)), $tokenInfo);

		$params = array(
			'fields'       => 'id,first_name,last_name,link,birthday,picture.type(large)',
			'access_token' => $tokenInfo['access_token']
		);
		$userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?'
			. urldecode(http_build_query($params))), true);

		if (!isset($userInfo['id'])) {
			$this->resultError();
		}
		$userInfo['social_net_name'] = 'fb';
		$userInfo['photo'] = $userInfo['picture']['data']['url'];

		$user = Social::setSocialUser($userInfo);
		if (is_null($user)) {
			$this->resultError();
		}

		return Redirect::back()
			->with('userMessage', 'Добро пожаловать на наш сайт!')
			->with('title', 'Вы успешно авторизовались!');
	}

	public function logout()
	{
		Auth::logout();

		return Redirect::back()->with('userMessage', 'Приходите к нам ещё.')
			->with('title', 'Всего доброго');
	}

	private function resultError()
	{
		return Redirect::to('register')
			->with('userMessage', "Что-то не так, попробуйте ещё раз")
			->with('title', 'Ошибка');
	}

}