<?php

namespace FintechFab\Components;

use Illuminate\Mail\Message;
use Config;
use Mail;

class MailSender
{


	public function doVanguardOrder(array $data)
	{
		Mail::send('emails.newImprover', $data, function (Message $message) {
			$message->to(Config::get('mail.recipient_order_form'))->subject('Новая заявка');
		});

		return (0 == count(Mail::failures()));
	}
	public function doVanguardOrderAuthor(array $data)
	{

		//return (0 == count(Mail::failures()));
		return (true);
	}


}