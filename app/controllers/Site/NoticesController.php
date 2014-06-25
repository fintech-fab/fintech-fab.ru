<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use FintechFab\Models\MessageThemes;
use FintechFab\Models\User;
use FintechFab\Models\Role;
use Input;
use Redirect;
use FintechFab\Components\MailSender;

class NoticesController extends BaseController
{

	public $layout = 'notices';

	public function notices()
	{
		$role = Role::whereRole('messageSubscriber')->first();

		return $this->make(
			'sendingNotices'
			, array('themes' => MessageThemes::all(array('id', 'name', 'comment'))
			, 'users' => $role->users
			)
		);
	}

	public function addNewTheme()
	{
		$mTheme = new MessageThemes;
		$mTheme->name = Input::get('themeName');
		$mTheme->message = Input::get('themeText');
		$mTheme->comment = Input::get('themeComment');
		$mTheme->theme = 'emails.themes';
		$mTheme->save();

		return Redirect::to('notices');
	}

	public function sendNotice()
	{
		if(! Input::has('themes'))
		{
			return Redirect::to('notices');
		}
		$mTheme = MessageThemes::find(Input::get('themes'));

		$subscribers = Input::get('subscribers');
		if(count($subscribers) == 0)
		{
			return Redirect::to('notices');
		}

		$data['subject'] = $mTheme->name;
		$data['baseMessage'] = $mTheme->message;
		$data['themeName'] = $mTheme->name;
		$data['comment'] = Input::get('comment', '');

		$mailSender = new MailSender();

		for($i = 0; $i < count($subscribers); $i++)
		{
			$user = User::find($subscribers[$i]);
			$data['to'] = $user->email;
			$data['toName'] = $user->first_name . " " . $user->last_name;
			$mailSender->doNoticeTheme($data);

		}

		return Redirect::to('notices');
	}

}