<?php

namespace FintechFab\Components;

use Config;
use Exception;
use FintechFab\Models\Role;
use Illuminate\Mail\Message;
use Mail;

class MailSender
{
	private $to;
	private $name;
	private $subject;

	public static function sendDinnerReminders()
	{
		$role = Role::whereRole('employee')->first();
		$users = $role->users;

		//Рассылаем напоминания всем найденным пользователям
		foreach ($users as $user) {
			Mail::send('emails.dinner', array(), function (Message $message) use ($user) {
				$message->to($user->email, $user->first_name . ' ' . $user->last_name)
					->subject('Вы можете заказать обед');
			});
		}
	}

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

	/*
	 * Отправка напоминаний о возможности заказать обед
	 */

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
}