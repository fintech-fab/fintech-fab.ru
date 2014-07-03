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

}
