<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use File;
use Illuminate\Mail\Message;
use Mail;

class MainController extends BaseController
{

	public $layout = 'site';

	public function index()
	{
		return $this->make('main');
	}

	public function probation()
	{
		return $this->make('probation');
	}

	public function thank()
	{
		$username = $_POST['username'];
		$email = $_POST['email'];
		$about = $_POST['about'];
		File::append('1.txt', '23');
		$data = array('username' => $username, 'email' => $email, 'about' => $about);
		Mail::send('site/mail', $data, function (Message $message) {
			$message->to('tutoring64@gmail.com', '$username')->subject('Заявка.');
		});

		return $this->make('thank');
	}

	public function vk()
	{
		return $this->make('vk');
	}

	public function test()
	{
		return $this->make('test');
	}

}