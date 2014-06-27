<?php

namespace FintechFab\Components;

use Config;
use Illuminate\Mail\Message;
use Mail;
use Symfony\Component\Security\Acl\Exception\Exception;

class MailSender
{
	private $to;
	private $name;
	private $subject;

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

		Mail::send('emails.replyToNewImprover', $data, function (Message $message) {
			$message->to($this->to, $this->name)->subject('Принята заявка');
		});

		$cntFails = count(Mail::failures());
		return (0 == $cntFails);
	}
	/**
	 * @param array $data
	 * $data['baseMessage']
	 * $data['themeName']
	 * $data['comment']
	 *
	 * @return bool
	 */
	public function doNoticeTheme(array $data)
	{
		$this->initTo($data);

		Mail::send('emails.noticeThemes', $data, function (Message $message) {
			$message->to($this->to, $this->name)->subject($this->subject);
		});

		$cntFails = count(Mail::failures());
		return (0 == $cntFails);
	}


	/**
	 *
	 * @param      $data
	 * @param bool $defaultTo
	 * $data['to']
	 * $data['toName']
	 * $data['subject']
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
		$this->name = empty($data['toName'])
			? $this->getNameFromTo()
			: $data['toName'];
		$this->subject = isset($data['subject'])
			? $data['subject']
			: "";

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