<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use FintechFab\Models\MessageThemes;
use FintechFab\Models\User;
use FintechFab\Components\Helper;
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
				, 'users' => User::all() , 'subscribers' => '' )
		);
	}

	public function sendNotice()
	{
		$subscribers = Input::except('themes','message','name','comment','_token');
		if(count($subscribers) == 0)
		{
			Redirect::to('notices');
		}

		$mails = DB::select(
			"SELECT email, CONCAT(first_name, ' ', last_name ) AS name FROM users WHERE id in (" . implode(', ',$subscribers) . ");");

		//$mails= User::whereRaw('id in (?)', array(implode(',',$subscribers)) )->get(array('email'));
		//for test
		$mails_1= User::whereRaw('id in (' . implode(',',$subscribers) . ')' )->get(array('email'));
		//for test
		$mails_2 = DB::select("SELECT email FROM users WHERE id in (?);"
			,  array(implode(', ',$subscribers))
		);
		//for test
		$mails_3 = DB::select("SELECT email, CONCAT(first_name, ' ', last_name ) AS name FROM users WHERE id in (" . implode(', ',$subscribers) . ");"
		);
		//for test
		$mails_4 = DB::select("SELECT email FROM users WHERE ? "
			,  array( ("id in (" . implode(', ',$subscribers) . ")") )
		);
		//for test
		$mails_5 = DB::select(sprintf("SELECT email FROM users WHERE id in (%s);", implode(', ',$subscribers) )	);

		$m['m2'] = $mails_2; //for test
		$m['m3'] = $mails_3; //for test
		$m['m4'] = $mails_4; //for test
		$m['m5'] = $mails_5; //for test
		$m['m1'] = $mails_1; //for test


		$mTheme = MessageThemes::find(Input::get('themes'));

		$data['subject'] = $mTheme->name;
		$data['baseMessage'] = $mTheme->message;
		$data['themeName'] = $mTheme->name;
		$data['comment'] = Input::get('comment', '');

		$strMails = ''; //for test
		$i=0;           //for test
		$dt = array(); //for test
		$m['m6'] = $mTheme; //for test

		//$mailSender = new MailSender();
		foreach($mails as $mail)
		{
			$data['to'] = $mail->email;
			$data['toName'] = $mail->name;
			//$mailSender->doNoticeTheme($data);


			$strMails .= $mail->email . ';'; //for test
			$dt[$i] = $data; //for test
			$i += 1; //for test
		}



		$strMails = rtrim($strMails, ';'); //for test
		$data['to'] = $strMails; //for test

		$m['m6'] = $strMails;   //for test
		$m['m7'] = $dt;         //for test

		//for test
		return $this->make(
			'sendingNotices'
			, array('themes' => MessageThemes::all(array('id', 'name', 'comment'))
			, 'users' => User::all() , 'subscribers' => $m) //$subscribers )
		);
	}

}