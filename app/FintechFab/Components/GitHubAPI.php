<?php
/**
 *  Применение
 *
 *  начало:
 * 		$gitHubAPI = new GitHubAPI();
 *      $gitHubAPI->setRepo($owner, $repository); //('fintech-fab', 'fintech-fab.ru');
 *
 * запросы:
 *      $gitHubAPI->setNewRepoQuery($contentOfRepository, $params); //('issues/comments') | ('')
 *		while($gitHubAPI->doNextRequest())
 *		{
 *            //
 *		}
 *
 *----------------------------------------------
 *
 *  Вывод сообщений  в команде "php artisan"
 *
 *			$this->info("Limit remaining: " . $gitHubAPI->getLimitRemaining());
 *			$this->info("Результат запроса: " . $gitHubAPI->messageOfResponse);
 *
 *      //сообщения в случае неудач:
 *      if(! $gitHubAPI->isDoneRequest())
 *      {
 *          $this->info("Результат выполнения запроса: " . $gitHubAPI->messageOfResponse);
 *      }
 *
 *       // вывод сообщений об ограничении запросов:
 *       $this->info($gitHubAPI->getLimit());
 *
 *
 */
namespace FintechFab\Components;


class GitHubAPI
{
	const BASE_URL = 'https://api.github.com/'; //адрес API GitHub'а (не изменяемый)

	/**
	 * Репозиторий, для которого создаются запросы к API GitHub
	 * (запрос данных о самом репозитории, или же в строку адреса добавляется уточнение
	 *  о конкретном содержимом репозитория: задачи, коммиты и пр.)
	 *
	 * @var string
	 */
	private $workRepo = '';
	/**
	 * @param string $owner
	 * @param string $repo
	 */
	public function setRepo($owner, $repo)
	{
		$this->workRepo = self::BASE_URL . 'repos/' . $owner . '/' . $repo;
	}

	//Исполняемые запросы
	private $startUrl = '';   //информативно, о первом запросе в цепочке запросов
	private $currentUrl = ''; //подготовленный к выполнению
	private $usedUrl = '';    //выполненный
	private $isDone = false;  // выполнен ли запрос?

	/**
	 * Завершение подготовки запроса.
	 * Задается конкретное содержимое репозитория, о котором будет запрос к API GitHub
	 * @param string $repoData
	 * @param string $params
	 */
	public function setNewRepoQuery($repoData = '', $params = '')
	{
		if($this->workRepo == '')
		{
			$this->startUrl = '';
			$this->currentUrl = '';
		}else
		{
			$repoData = ($repoData == '') ? '' : ('/' . $repoData);
			$this->startUrl = $this->workRepo .
				$repoData .
				($params = "" ? "" : ("?" . $params));
			$this->currentUrl = $this->startUrl;
			$this->usedUrl = '';
		}
	}


	/**
	 * Заголовок ответа
	 * @var array
	 */
	var $header = array();

	/**
	 * Данные ответа из GitHub API
	 * @var mixed
	 */
	var $response;

	/**
	 * Описание ошибки curl
	 * @var string
	 */
	var $error = '';

	/**
	 * Номер ошибки curl
	 * @var integer
	 */
	var $errno = 0;

	/**
	 * Сообщение о результате последнего запроса к API GitHub
	 * @var string
	 */
	var $messageOfResponse = '';

	/**
	 * Выполнять запросы к API GitHub через этот метод
	 * Адрес запроса уже должен быть готов (содержится в $this->currentUrl)
	 *
	 * @return bool
	 */
	public function doNextRequest()
	{
		$this->messageOfResponse = '';
		if($this->currentUrl == '')
		{
			return false;
		}
		else
		{
			$status = $this->doGitHubRequest($this->currentUrl);
			switch($status)
			{
				case 0:
					$this->messageOfResponse = "Error number: {$this->errno} \r\n{$this->error} \r\n";
					break;
				case 200:
					$this->messageOfResponse = 'OK';
					break;
				case 304:
					$this->messageOfResponse = 'Запрос выполнен успешно. Новых данных нет.';
					break;
				case 403:
					if(isset($this->header['X-RateLimit-Remaining']))
					{
						if($this->header['X-RateLimit-Remaining'] == 0)
						{
							$this->messageOfResponse .= "Лимит запросов исчерпан. \nВозобновить можно после: "
								 . date("c", $this->header['X-RateLimit-Reset']) . "\n";
						}
					}
			}
			if(!($status == 0 || $status == 200))
			{
				$this->messageOfResponse .= isset($this->header['Status']) ?
					"Status: {$this->header['Status']} \n" :
					"Status: $status \n";
				if(isset($this->response->message))
				{
					$this->messageOfResponse .= $this->response->message;
				}
			}

			$this->usedUrl = $this->currentUrl;
			if(isset($this->header['Link']['next']))
			{
				$this->currentUrl = $this->header['Link']['next'];
			}else
			{
				$this->currentUrl = '';
			}
		}
		$this->isDone = ($this->messageOfResponse == 'OK');

		return $this->isDone;
	}

	/**
	 * @return bool
	 */
	public function isDoneRequest()
	{
		return $this->isDone;
	}


	/**
	 * Получает данные из GitHub API
	 * Сохраняет их в разобранной форме:
	 * заголовок $this->header и данные $this->response
	 *
	 * @param string $url
	 *
	 * Возврщает код HTTP
	 * @return integer
	 */
	private function doGitHubRequest($url)
	{
		$this->error = '';
		$this->errno = 0;
		$this->header = array();
		$this->response = '';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "fintech-fab");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); //без перенаправлений на другой адрес


		$response = curl_exec($ch);
		if(curl_errno($ch) != 0){
			$this->error = curl_error($ch);
			$this->errno = curl_errno($ch);
			curl_close($ch);
			return 0;
		}

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$pos = strpos($response, "\r\n\r\n"); //альтернативный вариант отделения заголовка
		$strArray = ($pos === false) ? array() : explode("\r\n", substr($response, 0, $pos));//альтернативный вариант отделения заголовка
		$response = trim(substr($response, $pos)); //альтернативный вариант отделения заголовка
		$this->response = json_decode($response); //альтернативный вариант отделения заголовка

		for($i = 1; $i < count($strArray); $i++)
		{
			$pos = strpos($strArray[$i], ":");
			if($pos > 0)
			{
				$this->header[substr($strArray[$i], 0, $pos)] = substr($strArray[$i], $pos + 1);
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
	private static function decodePageLinks($inLinks)
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
	 * Лимит доступного количества запросов к API GitHub
	 * @return int
	 */
	public function getLimitRemaining()
	{
		if(! isset($this->header['X-RateLimit-Remaining']))
		{
			$this->doGitHubRequest(self::BASE_URL . 'rate_limit');
		}

		if(isset($this->header['X-RateLimit-Remaining']))
		{
			return $this->header['X-RateLimit-Remaining'];
		}else
		{
			return 0;
		}

	}

	/**
	 * Лимит доступа к API GitHub
	 * @return string
	 */
	public function getLimit()
	{
		if(! isset($this->header['X-RateLimit-Remaining']))
		{
			$this->doGitHubRequest(self::BASE_URL . 'rate_limit');
		}
		if(isset($this->header['X-RateLimit-Remaining']))
		{
			return (
				"Rate limit: " . $this->header['X-RateLimit-Limit'] . "\r\n" .
				"Limit remaining: " . $this->header['X-RateLimit-Remaining'] . "\r\n" .
				'Limit reset: '. date("c", $this->header['X-RateLimit-Reset'])
			);

		}else
		{
			return "";
		}

	}

	/**
	 * @return string
	 */
	public function getLastUrl()
	{
		return $this->usedUrl;
	}


}