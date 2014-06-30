<?php


namespace FintechFab\Components\Form\Vanguard;


use Form;

class FormHelper {

	public static function input($name, $placeholder, $required = true)
	{
		echo '<div class="col-sm-8">';
		echo Form::input('text', $name, '', array(
			'placeholder' => $placeholder,
			'class'       => 'form-control',
			'id'          => 'input' . ucfirst($name),
			'required'    => $required,
		));
		echo '</div>';
	}

} 