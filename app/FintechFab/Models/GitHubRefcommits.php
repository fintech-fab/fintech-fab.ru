<?php

namespace FintechFab\Models;

use Eloquent;


/**
 * Class GitHubRefcommits
 *
 * @package FintechFab\Models
 *
 * @property integer $id
 * @property string  $actor_login
 * @property string  $commit_id
 * @property integer $created
 * @property integer $issue_number
 * @property string  $message
 *
  */
class GitHubRefcommits extends Eloquent
{
	public $timestamps = false;

	protected $table = 'github_refcommits';

	public function issue()
	{
		return GitHubIssues::where("number", $this->issue_number)->first();
	}

	public function user()	{
		return GitHubMembers::find($this->actor_login);
	}


	public function getKeyName()
	{
		return 'id';
	}
	public function getMyName()
	{
		return 'issue commit';
	}

	public function dataGitHub($inData)
	{
		if($inData->event != 'referenced'){
			return false;
		}
		$this->id = $inData->id;
		$this->commit_id = $inData->commit_id;
		$this->actor_login = $inData->actor->login;
		$this->created = $inData->created_at;
		$this->issue_number = $inData->issue->number;
		if(isset($inData->message))
		{
			$this->message = $inData->message;
		}
		return true;
	}
	public function updateFromGitHub($inData)
	{
		if(isset($inData->message))
		{
			if($this->message == '')
			{
				$this->message = $inData->message;
				return true;
			}
		}
		return false;
	}
}