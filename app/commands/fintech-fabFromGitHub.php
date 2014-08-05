<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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
	// я имел ввиду fintech-fab:git-hub :)
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
	// конструктор не может вовзвращать void (и IDE ругается здесь)
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

		// красота. только потом между каждым case и break не должно появиться много кода
		// строчек по 10 максимум, если получится - это будет отлично.
		// и здесь не должно быть никакой логики, только запуск соответствующих обработок
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
		// лучше не усложнять названия, не dataCategory, а просто category или type и т.п.
		// но при этом чтобы оставалось понятным, конечно :-)
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
		// лучше не усложнять названия, не helpArg, а просто help
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
	// нет обработки ошибок! в случае ошибки может не вернуться код http
	//      кроме этого, в чем польза - возвращать код http?
	//      зачем он может понадобится тому, кто будет пользоваться этим методом?
	//
	// названия продумать:
	// - метод - getFromGitHubApi - "получить из гит-хаб апи"
	//          на самом деле метод возвращает http-код из http-запроса, а не "получает из гит-хаб апи"
	//          и согласно этому должен называться getHttpCodeFromGitHubApiRequest (получить код http из запроса к гитхаб-апи)
	//          но если еще внимательнее посмотреть, метод выполняет запрос к гит-хаб апи, получает из него некоторые данные,
	//          сохраняет их во внутренних свойствах объекта, и поэтому идеальным названием будет doGitHubRequest (выполнить запрос к гит-хабу)
	//          и возвращать лучше bool - успешно или нет или совсем ничего не возвращать.
	//          нужно крайне внимательно относиться к тому, что делает метод и зачем он возвращает именно это
	// - параметр - $httpRequest - это же на самом деле всего лишь url, а называется как целый протокол...
	//
	// ну и напомню свое предложение - все запросы к гит-хабу (не разбор конкретных полученных данных, а именно запросы)
	// с этим необязательно разбираться сейчас, но потом все равно придется :-)
	// лучше делать отдельным объектом "общение" с гит-хабом
	// т.к. у самой команды задача - не к гитхабу обращаться, а управлять процессом в целом.
	// сейчас один класс:
	//      - получает и разбирает запрос от пользователя, решает что будет делать
	//      - выполняет http-запросы к гит-хабу
	//      - хранит в себе результаты http-запросов от гит-хаба
	//      - знает про http-код запроса
	//      - разбирается с постраничной навигацией
	//      - разбирается с тем, куда и как сохранять данные от гит-хаба
	// слишком много обязанностей на одной сущности.
	// см. первый принцип ООП: https://ru.wikipedia.org/wiki/SOLID_(объектно-ориентированное_программирование)
	protected function getFromGitHubApi($httpRequest)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $httpRequest);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "fintech-fab");

		// 1)
		// curl_exec может не выполниться по многим причинам
		// например url неверный или сеть упала, или гитхаб не отвечает
		// 2)
		// надо разделять функционал. плохо писать вызовы внутри вызовов
		// $response =  curl_exec($ch);
		// $strArray = explode("\r\n", $response);
		$strArray =  explode("\r\n", curl_exec($ch));
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		// разделять функционал, не делать вызовы внутри вызовов (см. выше)
		//
		// это какой то странный (ненадежный) способ получить нужные данные из ответа
		// а вдруг там появится лишний перенос строки?
		// или совсем не то, что ожидалось?
		$this->response = json_decode(array_pop($strArray));

		// есть способ проще: http://php.net//manual/ru/function.http-parse-headers.php
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
	// здесь разумно сделать интерфейс, от которого наследуются все модели, которые предназначены для сбора данных с гит-хаба
	// например интерфейс называется GitHubModelInterface
	// он обязательно реализует все указанные методы
	// и его можно указать здесь:
	// private function saveInDB($inData, GitHubModelInterface $classDB)
	// тогда все модели для гит-хаба будут подчиняться общим правилам (и как бонус - IDE ругаться не будет)
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
