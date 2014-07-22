<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * Class Terminal
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @property int    $id
 * @property string $name
 * @property string $url
 * @property string $queue
 * @property string $key
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 */
class Terminal extends Eloquent
{
	protected $table = 'terminals';
}

 