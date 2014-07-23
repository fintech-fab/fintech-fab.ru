<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use FintechFab\Models\GitHubMembers;
use FintechFab\Models\GitHubIssues;

class testGitHubApi extends Command
{
	private static $apiBaseUrl = 'https://api.github.com/';

	private $_curl_nobody = false;
	private $_rateLimit = 0;
	private $_rateLimitRemaining = 0;
	private $_rateLimitReset = 0;

	private $apiRepos;


	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:testGitHubApi';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for testing GitHub Api';


	public function __construct()
	{
		parent::__construct();
		$this->apiRepos = "https://api.github.com/repos/fintech-fab/fintech-fab.ru/";

	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info("It's OK. Begin...");

		//$res = $this->getFromGitHubApi("https://api.github.com/users/fintech-fab");
		//$res = $this->getFromGitHubApi("https://api.github.com/repos/fintech-fab/fintech-fab.ru/issues/9/events");

		$opt = $this->option();
		$dt = getdate();
		$qDate = date('c', $dt[0]-(3600*24*40));

		switch($this->argument('firstArg')) {
			case "comments":
				$res = $this->getFromGitHubApi($this->apiRepos . "issues/comments?since=" . $qDate, "issuesCommentsData");
				break;
			case "commits":
				$res = $this->getFromGitHubApi($this->apiRepos . "commits?since=" . $qDate, "commitsData");
				break;
			case "events":
				$res = $this->getFromGitHubApi($this->apiRepos . "events", "eventsData");
				break;
			case "issues":
				/**?state=open|closed|all (Default: open)
				 * ?since='YYY-MM-DDTHH:MM:SSZ'
				 * ?sort=created|updated|comments (Default: created)
				 * ?direction=asc|desc
				 */
				if(! empty($opt["saveInDB"])) {
					$res = $this->getFromGitHubApi($this->apiRepos . "issues?state=all&direction=asc");
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubIssues');
					$res = '';
				}
				else {
					$res = $this->getFromGitHubApi($this->apiRepos . "issues?state=all", "issuesData");
				}
				break;
			case "issuesEvents":
				$this->_curl_nobody = true;
				$res = $this->getFromGitHubApi($this->apiRepos . "issues/events?page=2", "issuesEventsData");
				if(isset($res["header"]["Link"])) {
					$res = self::decodePageLinks($res["header"]["Link"]);
				}

				break;
			case "users":
				if(empty($opt["saveInDB"])) {
					$res = $this->getFromGitHubApi($this->apiRepos . "contributors");
					//$res = self::getFromGitHubApi($this->apiRepos . "assignees");
					//$res = self::getFromGitHubApi($this->apiRepos . "collaborators");
				}
				else {
					$this->info("contributors");
					$res = $this->getFromGitHubApi($this->apiRepos . "contributors");
					//$this->saveUsers($res['response']);
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubMembers');
					$this->info("assignees");
					$res = self::getFromGitHubApi($this->apiRepos . "assignees");
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubMembers');
					$res = '';
				}

				break;
			default:
				//$res = $this->argument();
				//$res = $this->getFromGitHubApi("https://api.github.com/orgs/fintech-fab/members");
				//$res = $this->option();
				$res = GitHubMembers::find('finking')->issues()->toArray();
		}



		ob_start();
		print_r($res);
		$d =  ob_get_clean();
		$this->info($d);
		$this->info("rateLimit: " . $this->_rateLimit);
		$this->info("rateLimitRemaining: " . $this->_rateLimitRemaining);
		$this->info("rateLimitReset: " . date("c", $this->_rateLimitReset));


	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('firstArg', InputArgument::OPTIONAL, 'An argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('saveInDB', null, InputOption::VALUE_NONE, 'An option.', null),
		);
	}
	protected function getFromGitHubApi($httpRequest, $func = '')
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $httpRequest);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "fintech-fab");

		if($this->_curl_nobody){
			curl_setopt($ch, CURLOPT_NOBODY, 1);
			$func = '';
		}

		//curl_setopt($ch, CURLOPT_USERPWD, ":");


		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$res = explode("\r\n", $response);
		$response = array_pop($res);
		$header = array();
		for($i = 1; $i < count($res); $i++)
		{
			$p = strpos($res[$i], ":");
			if($p > 0)
			{
				$header[substr($res[$i], 0, $p)] = substr($res[$i], $p + 1);
			}
		}
		$fullResponse = array();
		 $this->_rateLimit = $header['X-RateLimit-Limit'];
		 $this->_rateLimitRemaining = $header['X-RateLimit-Remaining'];
		 $this->_rateLimitReset = intval($header['X-RateLimit-Reset']);
		$fullResponse['header'] = $header;


		if($http_code == 200)
		{
			if($func == '')
			{
				$fullResponse['response'] = json_decode($response);
				return $fullResponse;
			} else
			{
				$res = array();
				foreach(json_decode($response) as $inData)
				{
					$res[] = self::$func($inData);
				}
				$fullResponse['response'] = $res;
				return $fullResponse;
			}

		} else {
			return $http_code;
		}
	}

	/**
	 * @param string $inLinks
	 *
	 * @return array
	 */
	protected static function decodePageLinks($inLinks)
	{
		$rel = "";
		$links = explode(",", $inLinks);
		$pageLinks = array();
		foreach($links as $strLink)
		{
			$link = explode(";", $strLink);
			parse_str($link[1]);
			$pageLinks[trim($rel, ' "')] = trim($link[0], " <>");
		}
		return $pageLinks;
	}

	protected static function commitsData($inData)
	{
		$x = array();
		$x['html_url'] = $inData->html_url;
		$x['date'] = $inData->commit->author->date;
		$x['message'] = $inData->commit->message;
		$x['authorName'] = $inData->commit->author->name;
		$x['committerName'] = $inData->commit->committer->name;
		$x['author'] = self::isNull(array($inData->author, "login")); //empty($inData->author->login) ? '' : $inData->author->login;
		$x['committer'] = self::isNull(array($inData->committer, "login")); //empty($inData->committer->login) ? '' : $inData->committer->login;
		return $x;
	}

	protected static function eventsData($inData)
	{
		$x = array();
		$x['id'] = $inData->id;
		$x['type'] = $inData->type;
		$x['actorLogin'] = $inData->actor->login;
		$x['created_at'] = $inData->created_at;
		return $x;
	}

	protected static function issuesCommentsData($inData)
	{
		$x = array();
		$x['html_url'] = $inData->html_url;
		$x['issue_url'] = $inData->issue_url;
		$n = explode('/', $x['issue_url']);
		$x['issue_num'] = $n[count($n)-1];
		$x['created_at'] = $inData->created_at;
		$x['updated_at'] = $inData->updated_at;
		$x['user'] = $inData->user->login;
		return $x;

	}

	protected static function issuesEventsData($inData)
	{
		$x = array();
		$x['id'] = $inData->id;
		$x['event'] = $inData->event;
		$x['actorLogin'] = $inData->actor->login;
		$x['commit_id'] = $inData->commit_id;
		$x['created_at'] = $inData->created_at;
		$x['issue'] = $inData->issue->number;
		return $x;
	}

	protected static function issuesData($inData)
	{
		$x = array();
		$x['html_url'] = $inData->html_url;
		$x['number'] = $inData->number;
		$x['title'] = $inData->title;
		$x['state'] = $inData->state;
		$x['created_at'] = $inData->created_at;
		$x['updated_at'] = $inData->updated_at;
		$x['closed_at'] = self::isNull($inData->closed_at);
		$x['user'] = $inData->user->login;
		return $x;

	}

	/**
	 * @param        $inVal
	 * @param string $val
	 *
	 * @return string
	 */
	private static function isNull($inVal = null, $val = '')
	{
		if(is_array($inVal))
		{
			if(empty($inVal[0])){
				return '';
			} else {
				$x = $inVal[0];
				for($i = 1; $i < count($inVal); $i++)
				{
					if(empty($x->$inVal[$i])){
						return '';
					} else {
						$x = $x->$inVal[$i];
					}
				}
				return $x;
			}
		}
		return empty($inVal) ? $val : $inVal;
	}

	/*
	private function saveUsers($inData)
	{
		$this->info("Addition to DataBase...");
		foreach($inData as $inMember)
		{
			$member = GitHubMembers::where("login", $inMember->login)->first();
			if(isset($member->login))
			{
				$this->info("User: " . $member->login);
				if(! empty($inMember->contributions))
				{
					if($member->contributions != $inMember->contributions)
					{
						$this->info("Update: " . $member->login);
						$member->contributions = $inMember->contributions;
						$member->save();
					}
				}
			}
			else
			{
				$this->info("Addition user: " . $inMember->login);
				$member = new GitHubMembers;
				$member->dataGitHub($inMember);
				$member->save();
			}
		}
	}
	private function saveIssues($inData)
	{
		$this->info("Addition to DataBase...");
		foreach($inData as $inItem)
		{
			$item = GitHubIssues::where("number", $inItem->number)->first();
			if(isset($item->id))
			{
				$this->info("Issue: " . $item->number);
				if($item->updateFromGitHub($inItem))
				{
					$this->info("Update: " . $item->number);
					$item->save();
				}
			}
			else
			{
				$this->info("Addition issue: " . $inItem->number);
				$item = new GitHubIssues;
				$item->dataGitHub($inItem);
				$item->save();
			}
		}
	}
	*/

	/**
	 * @param $inData
	 * @param $classDB
	 *
	 */
	private function saveInDB($inData, $classDB)
	{
		$this->info("Addition to DataBase...");
		$item = new $classDB;
		$keyName = $item->getKeyName();
		$myName = $item->getMyName();
		foreach($inData as $inItem)
		{
			$item = $classDB::where($keyName, $inItem->$keyName)->first();;
			if(isset($item->$keyName))
			{
				$this->info("Found $myName:" . $item->$keyName);
				if($item->updateFromGitHub($inItem))
				{
					$this->info("Update: " . $item->$keyName);
					$item->save();
				}
			}
			else
			{
				$this->info("Addition $myName: " . $inItem->$keyName);
				$item = new $classDB;
				$item->dataGitHub($inItem);
				$item->save();
			}
		}
	}


}
