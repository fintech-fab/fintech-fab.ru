<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $id_vk
 * @property string  $first_name
 * @property string  $last_name
 * @property integer $id_role
 * @property integer $link_vk
 * @property string  $updated_at
 * @property string  $created_at
 *
 * @method UserVk static where($column)
 */
class UserVk extends Eloquent
{

	protected $fillable = array('vk_id', 'first_name', 'last_name', 'email');

	protected $table = 'users_vk';

	public function getName()
	{
		return trim($this->first_name . ' ' . $this->last_name);
	}

}