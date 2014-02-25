<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Config;
use FintechFab\Components\Helper;
use Illuminate\Mail\Message;
use Mail;
use Redirect;
use Session;

class MainController extends BaseController
{

	public $layout = 'site';

	public function index()
	{
		return $this->make('main');
	}

	public function probation()
	{
		$feedback = Session::get('userMessage');

		return $this->make('probation', array(
			'userMessage' => $feedback,
		));
	}

	public function thank()
	{
		$feedback = Session::get('userMessage');

		return $this->make('thank', array(
			'userMessage' => $feedback,
		));
	}

	public function order()
	{
		$username = $_POST['username'];
		$email = $_POST['email'];
		$about = $_POST['about'];
		$data = array('username' => $username, 'email' => $email, 'about' => $about);
		Mail::send('site.mail', $data, function (Message $message) {
			$message->to(Config::get('mail.recipient_order_form'))->subject('Заявка.');
		});

		if (0 == count(Mail::failures())) {
			$feedback = Helper::ucwords($data['username']);
			$result = Redirect::to('thank')->with('userMessage', $feedback);
		} else {
			$feedback = 'Что-то сломалось, попробуйте ещё раз';
			$result = Redirect::to('probation')->with('userMessage', $feedback);
		}

		return $result;
	}

}