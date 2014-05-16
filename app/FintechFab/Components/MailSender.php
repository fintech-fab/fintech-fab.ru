<?php

namespace FintechFab\Components;

use Illuminate\Mail\Message;
use Config;
use Mail;

class MailSender
{
	private $to;
	private $name;

	public function doVanguardOrder(array $data)
	{
		$this->to = isset($data['to']) ? $data['to'] : Config::get('mail.recipient_order_form');

		Mail::send('emails.newImprover', $data, function (Message $message) {
			$message->to($this->to)->subject('Новая заявка');
		});

		return (0 == count(Mail::failures()));
	}
	public function doVanguardOrderAuthor(array $data)
	{
		if (! isset($data['to']))
			return false;
		$this->to = $data['to'];
		$this->name = isset($data['name']) ? $data['name'] : $data['to'];

		/*
		 *
		 *
		 *
		 */

		//return (0 == count(Mail::failures()));
		return (true);
	}


}