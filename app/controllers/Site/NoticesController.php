<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use Config;
use FintechFab\Components\Helper;
use Input;
use Mail;
use Redirect;
use FintechFab\Components\MailSender;

class NoticesController extends BaseController
{

	public $layout = 'notices';

	public function notices()
	{
		return $this->make('sendingNotices');
	}



}