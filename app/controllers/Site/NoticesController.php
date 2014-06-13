<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use FintechFab\Models\MessageThemes;
use FintechFab\Models\User;
use Input;
use Redirect;
use FintechFab\Components\MailSender;
use Illuminate\Support\Facades\DB;

class NoticesController extends BaseController
{

	public $layout = 'notices';

	public function notices()
	{
		return $this->make(
			'sendingNotices'
			, array('themes' => MessageThemes::all(array('id', 'name', 'comment'))
				, 'users' => User::all() )
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
			"SELECT email, CONCAT(first_name, ' ', last_name ) AS name FROM users WHERE id in (" . implode(', ',$subscribers) . ");");

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