<?php
/**
 *                  НЕ СДАЕТСЯ НА РЕВЬЮ
 *
 */
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use FintechFab\Models\IGitHubModel;
use FintechFab\Models\GitHubMembers;
use FintechFab\Models\GitHubIssues;
use FintechFab\Models\GitHubRefcommits;

class testGitHubApi extends Command
{
	//Опция для curl_setopt()
	private $_curl_nobody = false;// При значении 'true' результат запроса будет без данных (пустым)

	//Ограничения API GitHub'а по количеству запросов и времени.
	private $_rateLimit = 0;
	private $_rateLimitRemaining = 0;
	private $_rateLimitReset = 0;

	//Адрес репозитория
	private $apiRepos;


	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'testGitHubApi';

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

		//Не удалять.
		//$res = $this->getFromGitHubApi("https://api.github.com/users/fintech-fab");
		//$res = $this->getFromGitHubApi("https://api.github.com/repos/fintech-fab/fintech-fab.ru/issues/9/events");

		$opt = $this->option();
		$dt = getdate();
		$qDate = date('c', $dt[0]-(3600*24*15));
		$this->info($qDate);

		switch($this->argument('firstArg')) {
			case "comments":
				if(! empty($opt["save"])) {
					$res = $this->getFromGitHubApi($this->apiRepos . "issues/comments");
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubComments');
					$res = '';
				}
				else {
					$res = $this->getFromGitHubApi($this->apiRepos . "issues/comments?since=" . $qDate, "issuesCommentsData");
				}
				break;
			case "commits":
				$res = $this->getFromGitHubApi($this->apiRepos . "commits?since=" . $qDate, "commitsData");
				break;
			case "events":
				$res = $this->getFromGitHubApi($this->apiRepos . "events?page=2", "eventsData");
				//Не удалять.
				//$res = $this->getFromGitHubApi($this->apiRepos . "events?since=" . $qDate, "eventsData");  //Параметры здесь не работают
				break;
			case "issues":
				/**?state=open|closed|all (Default: open)
				 * ?since='YYY-MM-DDTHH:MM:SSZ'
				 * ?sort=created|updated|comments (Default: created)
				 * ?direction=asc|desc
				 */
				if(! empty($opt["save"])) {
					$res = $this->getFromGitHubApi($this->apiRepos . "issues?state=all&direction=asc");
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubIssues');
					$res = '';
				}
				else {
					$res = $this->getFromGitHubApi($this->apiRepos . "issues?state=all", "issuesData");
				}
				break;
			case "issuesEvents":
				// и почему empty, а если я передам в опции save="не надо сохранять"? :-)
				// То будет ответ: "[RuntimeException] The "--save" option does not accept a value. "
				if(empty($opt["save"]))
				{
					$res = $this->getFromGitHubApi($this->apiRepos . "issues/events", "issuesEventsData");
				} else
				{
					$res = $this->getFromGitHubApi($this->apiRepos . "issues/events");
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubRefcommits');
					$refCommits = GitHubRefcommits::where('message', '')->get();
					foreach($refCommits as $ref)
					{
						$res = $this->getFromGitHubApi($this->apiRepos . "git/commits/" . $ref->commit_id);
						$this->info("rateLimitRemaining: " . $this->_rateLimitRemaining);
						if($ref->updateFromGitHub($res['response']))
						{
							$this->info('Update: ' . substr($ref->message, 0, 60));
							$ref->save();
						}
					}
					$res = '';
				}
				break;
			case "issuesEvent": // тестовый, то есть не рабочий пункт.
				$res = $this->getFromGitHubApi($this->apiRepos . "issues/events", "issuesEventsData");
				$event = $res['response'][0];
				$res = $this->getFromGitHubApi($this->apiRepos . "git/commits/" . $event['commit_id']);
				$event['message'] = $res['response']->message;
				$res = $event;
				break;
			case "testPages":  // тестовый, то есть не рабочий пункт.
				$this->_curl_nobody = true;
				$res = array();
				$i = 10; //страховка от зацикливания
				$isNextPage = true;
				$link['next'] = $this->apiRepos . "issues/events";
				//Не удалять.
				//$link['next'] = $this->apiRepos . "issues/events?since=" . $qDate; //Параметры здесь не работают
				$newRes = array();
				while($isNextPage && $i > 0)
				{
					$newRes = $this->getFromGitHubApi($link['next']);
					$link = isset($newRes["header"]["Link"]) ?
						self::decodePageLinks($newRes["header"]["Link"]) :
						'';
					$res[] = $link;
					$isNextPage = isset($link['next']);
					$this->info("rateLimitRemaining: " . $this->_rateLimitRemaining);
					$i--;
				}
				$res['end'] = $newRes["header"];
				if(isset($newRes["header"]["Link"])){
					$res['endLink'] = self::decodePageLinks($newRes["header"]["Link"]);
				}
				break;
			case "users":
				if(empty($opt["save"])) {
					$res = $this->getFromGitHubApi($this->apiRepos . "contributors");   //Не удалять.
					//$res = self::getFromGitHubApi($this->apiRepos . "assignees");     //Не удалять.
					//$res = self::getFromGitHubApi($this->apiRepos . "collaborators"); //Не удалять.
				}
				else {
					$this->info("contributors");
					$res = $this->getFromGitHubApi($this->apiRepos . "contributors");
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubMembers');

					$this->info("assignees");
					$res = $this->getFromGitHubApi($this->apiRepos . "assignees");
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubMembers');
					$res = '';
				}

				break;
			default:
				//тесты (сюда не смотреть)
				//$res = $this->argument();
				//$res = $this->getFromGitHubApi("https://api.github.com/orgs/fintech-fab/members");
				//$res = $this->option();
				//$res = GitHubMembers::find('finking')->issues()->toArray();
				//$res = GitHubComments::find(41154299)->issue();
				$com = GitHubIssues::find(7)->comments();
				$res = array();
				foreach($com as $comment)
				{
					$res[] = $comment->user();
				}
				$res[] = empty($opt["save"]) ? 'true' : 'false';
				$res[] = isset($opt["save"]) ? 'true' : 'false';

		}



		$this->info(print_r($res, true));
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
			array('save', null, InputOption::VALUE_NONE, 'An option.', null),
		);
	}

	/**
	 * @param string $httpRequest
	 * @param string $func
	 *
	 * @return array|mixed
	 */
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

		//КАТЕГОРИЧЕСКИ НЕ УДАЛЯТЬ!!!
		//curl_setopt($ch, CURLOPT_USERPWD, ":");
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('If-None-Match: "e1fe2d0c86ed010a4fe5608a264b50b5"'));


		$response = curl_exec($ch);

		$this->info(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);


/*
		//$hdr = http_parse_headers($response);
		$hdr = http_parse_headers($response);
		$this->info(print_r($hdr, true));
		*/



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
		 $this->_rateLimit = self::isNull($header['X-RateLimit-Limit'], 0);
		 $this->_rateLimitRemaining = self::isNull($header['X-RateLimit-Remaining'], 0);
		 $this->_rateLimitReset = self::isNull(intval($header['X-RateLimit-Reset']), 0);
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


	/**
	 * @param $inData
	 * @param string $classDB
	 *
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
	private function saveInDB($inData,  $classDB)
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


}
