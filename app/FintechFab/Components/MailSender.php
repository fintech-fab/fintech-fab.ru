<?php

namespace FintechFab\Components;

use Config;
use Mail;

class MailSender
{


	public function doVanguardOrder(array $data)
	{
		Mail::send('emails.newImprover', $data, function (Message $message) {
			$message->to(Config::get('mail.recipient_order_form'))->subject('Новая заявка');
		});

		//return (0 == count(Mail::failures()));
		return (true);
	}
	public function doVanguardOrderAuthor(array $author)
	{

		//return (0 == count(Mail::failures()));
		return (true);
	}


}