<?php


namespace FintechFab\Components\Form\Vanguard;

use Form;

class FormHelper
{

	public static function input($type, $name, $placeholder, $required = true)
	{
		echo '<div class="col-sm-8">';
		echo Form::input($type, $name, '', array(
			'placeholder' => $placeholder,
			'class'       => 'form-control',
			'id'          => 'input' . ucfirst($name),
			'required'    => $required,
		));
		echo '</div>';
	}


	public static function checkbox($value, $name)
	{
		echo Form::checkbox('direction[' . $value . ']', $value, false, array(
			'id' => 'input' . ucfirst($value),
		));
		echo ' <label class="control-label" for="input' . ucfirst($value) . '">' . $name . '</label><br>';
	}


	public static function directions()
	{
		$directions = Improver::getDirectionList();
		foreach ($directions as $directionKey => $value) {
			FormHelper::checkbox($directionKey, $value);
		}
	}


	public static function getInformation()
	{

		return $information = array(
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


	}

}
