<?php

namespace FintechFab\Models;

use Eloquent;


/**
 * Class GitHubComments
 *
 * @package FintechFab\Models
 *
 * @property integer $id
 * @property string  $html_url
 * @property integer $issue_number
 * @property integer $created
 * @property integer $updated
 * @property string  $user_login
 * @property string  $prev
 *
  */
class GitHubComments extends Eloquent implements IGitHubModel
{

	// почему не использованы стандартные timestamps?
	// в чем смысл - называть поля по своему?
	public $timestamps = false;

	protected $table = 'github_comments';

	public function issue()
	{
		return GitHubIssues::where("number", $this->issue_number)->first();
	}

	public function user()	{
		return GitHubMembers::find($this->user_login);
	}


	public function getKeyName()
	{
		return 'id';
	}
	public function getMyName()
	{
		return 'issue comment';
	}

	public function dataGitHub($inData)
	{
		$this->id = $inData->id;
		$this->html_url = $inData->html_url;
		$n = explode('/', $inData->issue_url);
		$this->issue_number = $n[count($n)-1];
		// к вопросу выше - зачем делать по своему?
		// есть же стандартные названия - created_at, updated_at
		$this->created = $inData->created_at;
		$this->updated = $inData->updated_at;
		$this->user_login = $inData->user->login;
		$this->prev = $this->trimCommentBody($inData->body);
		return true;
	}
	public function updateFromGitHub($inData)
	{
		if((str_replace(" ", "T", $this->updated) . "Z") == $inData->updated_at) //<-------
		{
			return false;
		}
		else
		{
			$this->updated = $inData->updated_at;
			$this->prev = $this->trimCommentBody($inData->body);
			return true;
		}
	}

	private function trimCommentBody($str)
	{
		$body = strip_tags($str);
		return  (mb_strlen($body) > 27)
			? (mb_substr($body, 0, 26) . "...")
			: $body;

	}

}