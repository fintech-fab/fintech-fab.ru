<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * Class MessageThemes
 *
 * @package FintechFab\Models
 *
 * @property integer         $id
 * @property string          $theme
 * @property string          $name
 * @property string          $message
 * @property string          $comment
 *
 */
class MessageThemes extends Eloquent
{
	protected $fillable = array('name', 'message', 'comment');

	protected $table = 'message_themes';


}