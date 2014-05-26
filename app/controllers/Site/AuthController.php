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
use Session;
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
			$result['backUrl'] = $this->getBackUrl();

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

		return Redirect::to($this->getBackUrl())
			->with('userMessage', $userMessage)
			->with('userMessageTitle', $title);

	}

	public function socialNet()
	{
		$current_url = basename(Request::server('REQUEST_URI'), ".php");
		$socialNetName = explode('?', $current_url);
		$url = $this->getBackUrl();
		$userInfo = GetSocialUser::$socialNetName[0]();

		$user = Social::setSocialUser($userInfo);

		if (is_null($user)) {
			GetSocialUser::resultError();
		}
		if (Auth::check()) {
			return Redirect::to($url);
		}

		Auth::login($user);

		return Redirect::to($url)
			->with('userMessage', 'Добро пожаловать на наш сайт!')
			->with('userMessageTitle', 'Вы успешно авторизовались!');
	}

	public function logout()
	{
		Auth::logout();

		return Redirect::back()->with('userMessage', 'Приходите к нам ещё.')
			->with('userMessageTitle', 'Всего доброго');
	}

	/**
	 * url для редиректа после авторизации/регистрации
	 *
	 * @return string
	 */
	private function getBackUrl()
	{

		$url = Session::get('authReferrerUrl');
		if ($url) {
			Session::remove('authReferrerUrl');

			return $url;
		}

		$url = Request::server('HTTP_REFERER');
		if ($url) {
			$validator = Validator::make(
				array('url' => $url),
				array('url' => 'required|url')
			);
			if ($validator->passes()) {
				return $url;
			}
		}

		return 'registration';

	}

}