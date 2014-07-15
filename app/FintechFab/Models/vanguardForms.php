<?php

namespace FintechFab\Models;

use Eloquent;

class vanguardForms extends Eloquent
{

	public $mass = array(
		'label'       => array(
			'name'      => 'Как звать-величать',
			'direction' => 'В каких направлениях хотите стажироваться',
			'projects'  => 'Ваши работы',
			'time'      => 'Время',
			'visit'     => 'Могу раз в неделю приезжать к вам в офис',
			'email'     => 'Email для получения ответа',
			'about'     => 'О себе',
		),
		'placeholder' => array(
			'name'     => 'Имя. Лучше с фамилией.',
			'projects' => 'Что успели сделать на практике',
			'time'     => 'Сколько часов в неделю есть на стажировку',
			'visit'    => array('yes' => 'Да', 'no' => 'Нет'),
			'email'    => 'your@mail.com',
			'about'    => 'Расскажите про себя в свободной форме, но обязательно на тему сотрудничества',
		),
		'improver'    => array(
			'name'      => 'Имя:',
			'direction' => 'Направление:',
			'projects'  => 'Работы:',
			'time'      => 'Время на стажировку:',
			'visit'     => 'Могу ли приезжать в офис:',
			'email'     => 'Email:',
			'about'     => 'О себе:',
		),
	);


	public function mass()
	{
		return $this->mass;
	}

}