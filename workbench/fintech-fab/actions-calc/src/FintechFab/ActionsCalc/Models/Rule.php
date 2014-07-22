<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * Class Rule
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @property int    $id
 * @property string $name
 * @property string $rule
 * @property bool   $flag_active
 * @property int    $terminal_id
 * @property int    $event_id
 * @property int    $signal_id
 * @property string created_at
 * @property string updated_at
 */
class Rule extends Eloquent
{

	private $table = 'rules';

	public function terminal()
	{
		return $this->belongsTo('Terminal', 'terminal_id');
	} // TODO: rules
}