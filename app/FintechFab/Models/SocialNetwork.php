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
 */
class SocialNetwork extends Eloquent
{

	protected $fillable = array('id_network', 'social_net_name', 'first_name', 'last_name', 'email', 'link');

	protected $table = 'users_social_networks';

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function getAuthIdentifier()
	{
		return $this->user_id;
	}

	public function getAuthPassword()
	{
		return $this->password;
	}

}