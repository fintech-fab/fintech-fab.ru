<?php

use FintechFab\Models\GitHubIssues;
use FintechFab\Models\GitHubRefcommits;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class testGitHubApi extends Command
{
	// все правильно, так лучше не делать, потому что ссылка может измениться
	//private static $apiBaseUrl = 'https://api.github.com/';

	// что это за параметры? что они делают, на что влияют? как ими пользоваться?
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
	// для команд и всего другого - использовать общий префикс вендора - fintech-fab
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
		// так не надо делать. все что может меняться, - выносить в конфиг
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

		//не надо коммитить не работающий код
		//$res = $this->getFromGitHubApi("https://api.github.com/users/fintech-fab");
		//$res = $this->getFromGitHubApi("https://api.github.com/repos/fintech-fab/fintech-fab.ru/issues/9/events");

		$opt = $this->option();
		$dt = getdate();
		// совсем непонятно, что здесь происходит. как минимум комментарий написать, что такое $qDate и почему нужны сложные вычисления?
		$qDate = date('c', $dt[0]-(3600*24*15));
		$this->info($qDate);

		// похоже команда запускается аргументами и какими-то условиями
		// нужно предусмотреть выполнение команды с параметром --help чтобы команда сама показала, как ей пользоваться :-)
		switch($this->argument('firstArg')) {
			case "comments":
				if(! empty($opt["save"])) {
					// срочно прекращать так называть переменные. $res - это что?
					// в данном случае $res - это комментарии.
					// ниже $res - это коммиты... это плохо.
					// нельзя в одной переменной (в одной области видимости) держать разное содержимое
					// и переменная должна называться так, чтобы название совпадало с содержимым.
					$res = $this->getFromGitHubApi($this->apiRepos . "issues/comments");
					// начиная с php 5.5 (а у нас 5.5) вместо FintechFab\Models\GitHubComments можно делать:
					// т.е. так: this->saveInDB($res['response'], GitHubComments::class);
					// но класс GitHubComments надо сделать use
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubComments');
					$res = '';
				}
				else {
					// тут можно сделать сильно "красивее", то есть - удобнее в использовании
					// например: $this->getFromGitHubApi($method, $params)
					// при этом $this->apiRepos - берется из конфига (спрятано внутри), $method - это issues/comments, или commits, а $params - это уже параметры
					$res = $this->getFromGitHubApi($this->apiRepos . "issues/comments?since=" . $qDate, "issuesCommentsData");
				}
				break;
			case "commits":
				$res = $this->getFromGitHubApi($this->apiRepos . "commits?since=" . $qDate, "commitsData");
				break;
			case "events":
				$res = $this->getFromGitHubApi($this->apiRepos . "events?page=3", "eventsData");
				// неработающий код - убирать.
				//$res = $this->getFromGitHubApi($this->apiRepos . "events?since=" . $qDate, "eventsData");  //Параметры здесь не работают
				break;
			case "issues":
				/**?state=open|closed|all (Default: open)
				 * ?since='YYY-MM-DDTHH:MM:SSZ'
				 * ?sort=created|updated|comments (Default: created)
				 * ?direction=asc|desc
				 */
				// вообще весь этот код называют "портянкой" :-)
				// всегда, в любых обстоятельствах, плохо писать много кода в одном методе
				// улучшить можно если сборку запросов к гитхабовскому апи, спрятать в небольшие методы например $this->getIssues()
				// будет еще лучше если будут небольшие методы типа getIssues()
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
				// непонятная опция... требуется комментарии, описание!
				// и почему empty, а если я передам в опции save="не надо сохранять"? :-)
				if(empty($opt["save"]))
				{
					$res = $this->getFromGitHubApi($this->apiRepos . "issues/events", "issuesEventsData");
				} else
				{
					// портянка! выносить функциональные вещи в отдельные методы/классы
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
			case "issuesEvent":
				// я совсем не понимаю что здесь происходит и как дальше обрабатывается $res, и что содержится в $res...
			$res = $this->getFromGitHubApi($this->apiRepos . "issues/events", "issuesEventsData");
				$event = $res['response'][0];
				$res = $this->getFromGitHubApi($this->apiRepos . "git/commits/" . $event['commit_id']);
				$event['message'] = $res['response']->message;
				$res = $event;
				break;
			case "testPages":
				$this->_curl_nobody = true;
				$res = array();
				$i = 10; //страховка от зацикливания
				$isNextPage = true;
				$link['next'] = $this->apiRepos . "issues/events";
				// нерабочий код - весь удалить, зачем он здесь?
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
					$res = $this->getFromGitHubApi($this->apiRepos . "contributors");
					// почему закомментировано, что это?
					//$res = self::getFromGitHubApi($this->apiRepos . "assignees");
					//$res = self::getFromGitHubApi($this->apiRepos . "collaborators");
				}
				else {
					$this->info("contributors");
					$res = $this->getFromGitHubApi($this->apiRepos . "contributors");
					//$this->saveUsers($res['response']);
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubMembers');

					$this->info("assignees");
					$res = $this->getFromGitHubApi($this->apiRepos . "assignees");
					$this->saveInDB($res['response'], 'FintechFab\Models\GitHubMembers');
					$res = '';
				}

				break;
			default:
				// что за тесты, зачем, как пользоваться?
				// весь нерабочий код убирать. А если нужен - можно делать ветки "для себя"
				// и там экспериментировать, чтобы не "засорять" рабочий код
				//тесты
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

		}


		// хаха. это зря. у функции print_r есть второй параметр ;)
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
			array('save', null, InputOption::VALUE_NONE, 'An option.', null),
		);
	}

	// что такое $func? что возвращает метод?
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

		// что это? если не нужно - убирать
		//curl_setopt($ch, CURLOPT_USERPWD, ":");
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('If-None-Match: "e1fe2d0c86ed010a4fe5608a264b50b5"'));


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
			// нельзя чтобы метод мог возвращать json и строку с кодом ответа и еще какие то варианты...
			// это плохо-плохо, методом неудобно пользоваться и вероятность ошибок очень высокая
			// метод должен возвращать всегда что-то четкое, одно.
			// например
			// метод должен возвращать разобранный json (если все хорошо) или null (если нет данных)
			// или
			// возвращает true (если запрос удался) или false (если запрос не удался), а
			// результат запроса сохраняет куда нибудь в свойство класса в строгом (например, только массив) формате
			// и тогда при использовании метода будет внятная логика, например
			// если метод вернул true, то в свойстве класса $this->response находится валидный ответ
			// а если метод вернул false, то всвойстве класса $this->error находятся данные об ошибке
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
		// ох как коментов не хватает... что это за метод? что делает?
		// название переменной - что в ней? что значит rel?
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
		// название переменной!!!!!
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

	// ох-ох.. совсем непонятный метод...
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

	//
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
		// закомментированный код - убирать!
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
	 * @param $classDB
	 *
	 */
	// совершенно непонятно работает метод. комментариев не хватает.
	private function saveInDB($inData, $classDB)
	{
		$this->info("Addition to DataBase...");
		$item = new $classDB;
		// phpstorm ругаетя что у $item нет метода getKeyName..
		// что тут такое, совсем непонятно
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
