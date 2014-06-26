<?php

namespace FintechFab\Components;

use Config;
use Illuminate\Mail\Message;
use Mail;
use Exception;

class MailSender
{
	private $to;
	private $name;

	/**
	 *
	 * @param array $data
	 *
	 * @return bool
	 */
	public function doVanguardOrder(array $data)
	{
		$this->initTo($data, true);

		Mail::send('emails.newImprover', $data, function (Message $message) {
			$message->to($this->to)->subject('Новая заявка');
		});

		$cntFails = count(Mail::failures());
		return (0 == $cntFails);
	}
	/**
	 *
	 * @param array $data
	 *
	 * @return bool
	 */
	public function doVanguardOrderAuthor(array $data)
	{
		$this->initTo($data);

		/*
		 *
		 *
		 *
		 */

		//$cntFails = count(Mail::failures());
		//return (0 == $cntFails);
		return (true);
	}


	/**
	 *
	 * @param      $data
	 * @param bool $defaultTo
	 *
	 * @throws Exception
	 */
	private function initTo($data, $defaultTo = false)
	{
		if (empty($data['to'])) {
			if ($defaultTo) {
				$data['to'] = Config::get('mail.recipient_order_form');
			}
			if (empty($data['to'])) {
				// на то, чего не должно быть, кидаем искючение
				throw new Exception('Не определен адрес получателя');
			}
		}
		$this->to = $data['to'];
		$this->name = empty($data['name'])
			? $this->getNameFromTo()
			: $data['name'];

	}
	/**
	 * Получим имя из адреса эл. почты
	 *
	 * @return mixed
	 */
	private function getNameFromTo()
	{
		$list = explode('@', $this->to);

		return $list[0];
	}
}