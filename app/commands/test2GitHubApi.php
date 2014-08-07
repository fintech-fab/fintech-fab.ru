<?php
/**
 *                  НЕ СДАЕТСЯ НА РЕВЬЮ
 *
 */
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use FintechFab\Models\GitHubComments;
use FintechFab\Models\IGitHubModel;
use FintechFab\Components\GitHubAPI;

class test2GitHubApi extends Command {

	/**
	 * @var GitHubAPI
	 */
	private $gitHubAPI;
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'test2GitHubApi';

	protected $description = 'Command for receiving of news from GitHub API.';

	/**
	 * Create a new command instance.
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$helpOption = $this->option('helpArg');
		if(! empty($helpOption))
		{
			$this->showHelp($helpOption);
			return;
		}

		$this->info("It's OK. Begin...");
		$this->gitHubAPI = new GitHubAPI();
		$this->gitHubAPI->setRepo('fintech-fab', 'fintech-fab.ru');
$res = array();

		switch($this->argument('dataCategory')) {
			case "comments":
				$this->gitHubAPI->setNewRepoQuery('issues/comments');
				//$res = $this->processTheData('FintechFab\Models\GitHubComments', 'issuesCommentsData');
				$res = $this->processTheData(GitHubComments::class, 'issuesCommentsData');
				break;
			case "commits":
				break;
			case "events":
				break;
			case "issues":
				$this->gitHubAPI->setNewRepoQuery("issues", "state=all");
				if($this->gitHubAPI->doNextRequest())	{
					$res = $this->editData($this->gitHubAPI->response, 'issuesData');
					$this->info("Limit remaining: " . $this->gitHubAPI->getLimitRemaining());
				}
				$this->info("Результат запроса: " . $this->gitHubAPI->messageOfResponse);

				break;
			case "issuesEvents":
				break;
			case "users":
				break;
			default:

		}

		$this->info(print_r($res, true));
		$this->info($this->gitHubAPI->getLimit());



	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('dataCategory', InputArgument::OPTIONAL, 'Category of data for request to GitHub API.'),
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
			array('helpArg', null, InputOption::VALUE_OPTIONAL, 'The help about used argument. --helpArg=*|all|:value_of_argument', null),
		);
	}

	/**
	 * Выводит на экран список используемых значений аргумента или детально по указанному значению
	 * @param string $option
	 */
	private function showHelp($option)
	{
		//
	}

	/**
	 * @param IGitHubModel  $dataModel
	 * @param string        $func
	 *
	 * @return array
	 */
	private function processTheData(IGitHubModel $dataModel, $func = '')
	{
		$res = array();
		while($this->gitHubAPI->doNextRequest())
		{
			//$res[] = $this->editData($this->gitHubAPI->response, $func);
			$this->info("Limit remaining: " . $this->gitHubAPI->getLimitRemaining());
			$this->info("Результат запроса: " . $this->gitHubAPI->messageOfResponse);
			$this->saveInDB($this->gitHubAPI->response, $dataModel);
		}
		if(! $this->gitHubAPI->isDoneRequest())
		{
			$this->info("Результат запроса: " . $this->gitHubAPI->messageOfResponse);
		}

		return $res;
	}



	/**
	 * Получает данные из GitHub API
	 * Сохраняет их в разобранной форме:
	 * заголовок $this->header и данные $this->response
	 *
	 * @param string $httpRequest
	 *
	 * Возврщает код HTTP
	 * @return integer
	 */
	protected function getFromGitHubApi($httpRequest)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $httpRequest);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "fintech-fab");

		$strArray =  explode("\r\n", curl_exec($ch));
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$this->response = json_decode(array_pop($strArray));

		$this->header = array();
		for($i = 1; $i < count($strArray); $i++)
		{
			$p = strpos($strArray[$i], ":");
			if($p > 0)
			{
				$this->header[substr($strArray[$i], 0, $p)] = substr($strArray[$i], $p + 1);
			}
		}
		if(isset($this->header["Link"]))
		{
			$this->header["Link"] = self::decodePageLinks($this->header["Link"]);
		}

			return $http_code;
	}

	/**
	 * GitHub выдает данные постранично. В заголовке ответа (header) дает ссылки на другие страницы.
	 *
	 * Из полученной строки функция выделяет адреса страниц и указатели, со значениями: first, next, prev, last
	 * Например:  <https://api.github.com/repositories/16651992/issues/events?page=1>; rel="first"
	 *
	 * @param string $inLinks
	 *
	 * @return array
	 */
	protected static function decodePageLinks($inLinks)
	{
		$rel = ""; //Приходит из GitHub'а
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

	/**
	 * @param array $inData
	 * @param IGitHubModel $classDB
	 *
	 * Сохранение или обновление данных в БД,
	 * вывод сообщений на экран по каждой отдельной записи данных (при добавлении в БД, при обновлении).
	 *
	 * $inData  — данные, полученные из GitHub'а
	 * $classDB — имя класса таблицы БД (модель). Заполение и контроль полученных данных выполняется в этой модели.
	 *      Методы, принимающие данные ("dataGitHub($inItem)", "updateFromGitHub($inItem)")
	 *      дают положительный ответ "true", если разрешено сохранять.
	 *
	 * $item->getKeyName() — имя ключевого поля (может быть 'id' или иным). Задается в модели данных.
	 * $item->getMyName()  — нужно для вывода на экран (показать, какие данные сохраняются).
	 */
	private function saveInDB($inData, IGitHubModel $classDB)
	{
		$this->info("Addition to DataBase...");
		$item = new $classDB;
		$keyName = $item->getKeyName();
		$myName = $item->getMyName();
		foreach($inData as $inItem)
		{
			$item = $classDB::where($keyName, $inItem->$keyName)->first();
			if(isset($item->$keyName))
			{
				$this->info("Found $myName:" . $item->$keyName);
				if($item->updateFromGitHub($inItem))
				{
					$this->info("Update: " . $item->$keyName);
					$item->save();
				}
			} else
			{
				$item = new $classDB;
				if($item->dataGitHub($inItem))
				{
					$this->info("Addition $myName: " . $inItem->$keyName);
					$item->save();
				}
			}
		}
	}


	private function editData($inDataArray, $func)
	{
		if(! is_array($inDataArray)){
			return "";
		}
		$res = array();
		foreach($inDataArray as $inData)
		{
			$res[] = self::$func($inData);
		}
		return $res;
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
		switch($inData->type)
		{
			case "CommitCommentEvent":
				$x['payload'] = array(
					'commentId' => $inData->payload->comment->id,
					'comment' => $inData->payload->comment->html_url
				);
				break;
			case "CreateEvent":
				$x['payload'] = array(
					'ref' => $inData->payload->ref,
					'ref_type' => $inData->payload->ref_type
				);
				break;
			case "DeleteEvent":
				$x['payload'] = array(
					'ref' => $inData->payload->ref,
					'ref_type' => $inData->payload->ref_type
				);
				break;
			case "DeploymentEvent": break;
			case "DeploymentStatusEvent": break;
			case "DownloadEvent": break;
			case "FollowEvent": break;
			case "ForkEvent": break; //-
			case "ForkApplyEvent": break;
			case "GistEvent": break;
			case "GollumEvent": break;
			case "IssueCommentEvent":
				$x['payload'] = array(
					'action' => $inData->payload->action,
					'issueNumber' => $inData->payload->issue->number,
					'commentHtml_url' => $inData->payload->comment->html_url
				);
				break;
			case "IssuesEvent":
				$x['payload'] = array(
					'action' => $inData->payload->action,
					'issueNumber' => $inData->payload->issue->number
				);
				break;
			case "MemberEvent": break;
			case "PageBuildEvent": break;
			case "PublicEvent": break;
			case "PullRequestEvent": break;
			case "PullRequestReviewCommentEvent": break;
			case "PushEvent":
				$x['payload'] = array(
					'push_id' => $inData->payload->push_id,
					'ref' => $inData->payload->ref,
					'head' => $inData->payload->head
				);
				break;
			case "ReleaseEvent": break;
			case "StatusEvent": break;
			case "TeamAddEvent": break;
			case "WatchEvent": break;
		}



		return $x;
	}

	protected static function issuesCommentsData($inData)
	{
		$x = array();
		$x['id'] = $inData->id;
		$x['html_url'] = $inData->html_url;
		$n = explode('/', $inData->issue_url);
		$x['issue_number'] = $n[count($n)-1];
		$x['created_at'] = $inData->created_at;
		$x['updated_at'] = $inData->updated_at;
		$x['userLogin'] = $inData->user->login;
		$body = strip_tags($inData->body);
		$x['prevbody'] = (mb_strlen($body) > 27)
			? (mb_substr($body, 0, 26) . "...")
			: $body;
		return $x;

	}

	protected static function issuesEventsData($inData)
	{
		$x = array();
		/*
		$x['id'] = $inData->id;
		$x['event'] = $inData->event;
		$x['actor'] = (object)array('login' => $inData->actor->login);
		$x['commit_id'] = $inData->commit_id;
		$x['created_at'] = $inData->created_at;
		$x['issue'] = (object)array('number' => $inData->issue->number);
		return (object)$x;
			*/
		$x['id'] = $inData->id;
		$x['event'] = $inData->event;
		$x['actorLogin'] = $inData->actor->login;
		$x['commit_id'] = $inData->commit_id;
		$x['created_at'] = $inData->created_at;
		$x['issueNumber'] = $inData->issue->number;
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


}
