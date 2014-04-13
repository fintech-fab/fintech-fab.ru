<?php
/**
 * @var array $userInfo
 */
namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Auth;
use FintechFab\Components\GetSocialUser;
use FintechFab\Components\Social;
use FintechFab\Components\WorkWithInput;
use FintechFab\Models\User;
use FintechFab\Widgets\LinksInMenu;
use Hash;
use Input;
use Redirect;
use Request;
use Validator;

class AuthController extends BaseController
{
	public $layout = 'auth';

	public function registration()
	{
		return $this->make('registration');
	}


	public function postAuth()
	{
		$data = Input::all();
		$remember = isset($data['remember']) ? true : false;

		$validator = Validator::make($data, WorkWithInput::rulesForInputAuth(), WorkWithInput::messagesForErrors());
		$userMessages = $validator->messages();
		$emailError = $userMessages->first('email');
		$passwordError = $userMessages->first('password');
		$result['errors'] = array($emailError, $passwordError);

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
				->with('userMessageTitle', $title)
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

		return Redirect::to($this->getRedirectBackUrl('/profile'))
			->with('userMessage', $userMessage)
			->with('userMessageTitle', $title);

	}

	public function socialNet()
	{
		$current_url = basename(Request::server('REQUEST_URI'), ".php");
		$socialNetName = explode('?', $current_url);

		$userInfo = GetSocialUser::$socialNetName[0]();

		$user = Social::setSocialUser($userInfo);

		if (is_null($user)) {
			GetSocialUser::resultError();
		}
		if (Auth::check()) {
			return Redirect::to('profile');
		}

		Auth::login($user);

		return Redirect::back()
			->with('userMessage', 'Добро пожаловать на наш сайт!')
			->with('userMessageTitle', 'Вы успешно авторизовались!');
	}

	public function logout()
	{
		Auth::logout();

		return Redirect::back()->with('userMessage', 'Приходите к нам ещё.')
			->with('userMessageTitle', 'Всего доброго');
	}

}