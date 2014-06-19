<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use FintechFab\Models\MessageThemes;
//use FintechFab\Models\User;
use Input;
use Redirect;
use FintechFab\Components\MailSender;
use DB;

class NoticesController extends BaseController
{

	public $layout = 'notices';

	public function notices()
	{
		$users =
			DB::table('users')
				->join('role_user', 'users.id', '=', 'role_user.user_id')
				->join('roles', 'role_user.role_id', '=', 'roles.id')
			->where('roles.role', 'messageSubscriber')
			->select('users.id', 'users.first_name', 'users.last_name')
			->get();

		$pr = DB::getTablePrefix();
		$users1 = DB::select("
			SELECT u.id, u.first_name, u.last_name
			fROM {$pr}users AS u INNER JOIN
				{$pr}role_user AS ru ON ru.user_id = u.id INNER JOIN
				{$pr}roles AS r ON r.id = ru.role_id
			WHERE r.role = 'messageSubscriber'");


		return $this->make(
			'sendingNotices'
			, array('themes' => MessageThemes::all(array('id', 'name', 'comment'))
			, 'users' => $users1 )
		);
	}

	public function sendNotice()
	{

		$subscribers = Input::except('themes','themeText','themeName', 'themeComment','comment','_token', 'btnSelectedTheme', 'btnNewTheme');

		if(Input::exists('btnNewTheme'))
		{
			$mTheme = new MessageThemes;
			$mTheme->name = Input::get('themeName');
			$mTheme->message = Input::get('themeText');
			$mTheme->comment = Input::get('themeComment');
			$mTheme->theme = 'emails.themes';
			$mTheme->save();
		}
		else
		{
			if(! Input::has('themes'))
			{
				return Redirect::to('notices');
			}
			$mTheme = MessageThemes::find(Input::get('themes'));
		}

		if(count($subscribers) == 0)
		{
			return Redirect::to('notices');
		}

		$mails = DB::select(
			"SELECT email, CONCAT(first_name, ' ', last_name ) AS name
			 FROM " . DB::getTablePrefix() . "users
			 WHERE id in (" . implode(', ',$subscribers) . ");");

		$data['subject'] = $mTheme->name;
		$data['baseMessage'] = $mTheme->message;
		$data['themeName'] = $mTheme->name;
		$data['comment'] = Input::get('comment', '');

		$mailSender = new MailSender();
		foreach($mails as $mail)
		{
			$data['to'] = $mail->email;
			$data['toName'] = $mail->name;
			$mailSender->doNoticeTheme($data);
		}

		return Redirect::to('notices');
	}

}