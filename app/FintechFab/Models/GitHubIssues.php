<?php

namespace FintechFab\Models;

use Eloquent;
//use FintechFab\Models\GitHubMembers;

/**
 * Class GitHubIssues
 *
 * @package FintechFab\Models
 *
 * @property string  $html_url
 * @property integer $number
 * @property string  $title
 * @property string  $state
 * @property integer $created
 * @property integer $updated
 * @property integer $closed
 * @property string  $user_login
 *
  */
class GitHubIssues extends Eloquent
{
	public $timestamps = false;
	protected $table = 'github_issues';

	public function user()	{
		//	return $this->belongsTo('FintechFab\Models\GitHubMembers', "login", "user_login");
		return GitHubMembers::find($this->user_login);

	}

	public function comments()	{
		//	return $this->belongsTo('FintechFab\Models\GitHubMembers', "login", "user_login");
		return GitHubComments::where("issue_number", $this->number)->orderBy("created")->get();

	}

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
		if(! isset(GitHubMembers::find($inData->user->login)->login))
		{
			$user = new GitHubMembers;
			$user->login = $inData->user->login;
			$user->save();
		}
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
		$this->user_login = $inData->user->login;
		return true;
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