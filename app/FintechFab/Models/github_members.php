<?php

namespace FintechFab\Models;

use Eloquent;
//use FintechFab\Models\GitHubIssues;

/**
 * Class GitHubMembers
 *
 * @package FintechFab\Models
 *
 * @property integer $id
 * @property string  $login
 * @property string  $avatar_url
 * @property integer  $contributions
 *
  */
class GitHubMembers extends Eloquent
{

	protected $table = 'github_members';

	public function issues()
	{
		//return $this->hasMany('FintechFab\Models\GitHubIssues', "userLogin");
		return GitHubIssues::where("userLogin", $this->login)->get();
	}


	public function getKeyName()
	{
		return 'login';
	}
	public function getMyName()
	{
		return 'user';
	}

	public function dataGitHub($inData)
	{
		$this->login = $inData->login;
		$this->avatar_url = $inData->avatar_url;
		if(! empty($inData->contributions)) {
			$this->contributions = $inData->contributions;
		}
	}
	public function updateFromGitHub($inData)
	{
		$changed = false;
		if($this->avatar_url != $inData->avatar_url)
		{
			$this->avatar_url = $inData->avatar_url;
			$changed = true;
		}
		if(! empty($inData->contributions))
		{
			if($this->contributions != $inData->contributions)
			{
				$this->contributions = $inData->contributions;
				$changed = true;
			}
		}
		return $changed;
	}


}