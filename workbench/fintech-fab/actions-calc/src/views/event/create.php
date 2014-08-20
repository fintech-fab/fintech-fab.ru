<?php
/**
 * File create.php
 * 
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

Form::open([
	'action' => 'EventController@create'
]);

Form::model(\FintechFab\ActionsCalc\Models\Event::getModel());

Form::submit('Добавить', ['class' => 'button small']);