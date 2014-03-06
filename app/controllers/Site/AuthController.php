<?php
/**
 * @var array $userInfo
 */
namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Config;
use FintechFab\Models\UserVk;
use Input;
use Redirect;

class AuthController extends BaseController
{

	public $layout = 'vanguard';

	public function postAuth()
	{
		$data = $this->getOrderFormData();
		dd($data);


		return Redirect::to('vanguard')->with('userMessage', $data);

	}

	private function getOrderFormData()
	{
		$email = Input::get('email');
		$password = Input::get('password');


		$data = array(
			'email' => $email,
			'password' => $password,
		);

		return $data;

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
				$this->make('vk');

				$user = UserVk::firstOrNew(array(
					'id_vk' => $id_vk,
				));

				$user->setAttribute('id_vk', $id_vk);
				$user->setAttribute('first_name', $first_name);
				$user->setAttribute('last_name', $last_name);
				$user->save();

			}

		}
	}
}