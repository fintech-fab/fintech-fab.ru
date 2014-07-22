<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * Class MembersGitHub
 *
 * property integer $id
 * property string  $login
 * property string  $avatar_url
 * property integer  $contributions
 *
 *
 * @package FintechFab\Models
 */
class MembersGitHub extends Eloquent
{
	//protected $fillable = array('login', 'avatar_url');
	//public $timestamps = false;

	protected $table = 'github_members';

	public function dataGitHub($inData)
	{
		$this->login = $inData->login;
		$this->avatar_url = $inData->avatar_url;
		if(! empty($inData->contributions)) {
			$this->contributions = $inData->contributions;
		}
	}


}