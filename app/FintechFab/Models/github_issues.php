<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * Class GitHubIssues
 *
 * @package FintechFab\Models
 *
 * @property integer $id
 * @property string  $html_url
 * @property integer $number
 * @property string  $title
 * @property string  $state
 * @property integer $created
 * @property integer $updated
 * @property integer $closed
 * @property string  $userLogin
 *
  */
class GitHubIssues extends Eloquent
{
	public $timestamps = false;
	protected $table = 'github_issues';

	public function getKeyName()
	{
		return 'number';
	}
	public function getMyName()
	{
		return 'issue';
	}


	public function dataGitHub($inData)
	{
		$this->html_url = $inData->html_url;
		$this->number = $inData->number;
		$this->title = $inData->title;
		$this->state = $inData->state;
		$this->created = $inData->created_at;
		$this->updated = $inData->updated_at;
		if(! empty($inData->closed_at))
		{
			$this->closed = $inData->closed_at;
		}
		$this->userLogin = $inData->user->login;
	}

	public function updateFromGitHub($inData)
	{
		$changed = false;
		if($this->html_url != $inData->html_url)
		{
			$this->html_url = $inData->html_url;
			$changed = true;
		}
		if($this->title != $inData->title)
		{
			$this->title = $inData->title;
			$changed = true;
		}
		if($this->state != $inData->state)
		{
			$this->state = $inData->state;
			$changed = true;
		}
		if(intval($this->updated) != intval($inData->updated_at))
		{
			$this->updated = $inData->updated_at;
			$changed = true;
		}
		if(is_null($this->closed) && (! is_null($inData->closed_at)))
		{
			$this->closed = $inData->closed_at;
			$changed = true;
		}
		return $changed;
	}


}