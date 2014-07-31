<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FintechFabFromGitHub extends Command {

	/**
	 * Заголовок http-ответа
	 * @var array
	 */
	private $header;

	/**
	 * Данные http-ответа из GitHub API
	 * @var mixed
	 */
	private $response;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:fintech-fabFromGitHub';




	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for receiving of news from GitHub API.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
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

		switch($this->argument('dataCategory')) {
			case "comments":
				break;
			case "commits":
				break;
			case "events":
				break;
			case "issues":
				break;
			case "issuesEvents":
				break;
			case "users":
				break;
			default:

		}




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
	 * Получает данные из GitHub API
	 * Сохраняет их в разобранной форме:
	 * заголовок $this->header и данные $this->response
	 *
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
	 * @param $classDB
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
	private function saveInDB($inData, $classDB)
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
