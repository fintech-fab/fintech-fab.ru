<?php

use FintechFab\Components\DinnerImportMenu as DinnerImportMenuComponent;
use FintechFab\Components\MailSender;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class DinnerImportMenu extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dinner:import-menu';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Импорт файла меню в базу данных';

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
		$url = $this->argument('url');

		//Если в параметрах команды задан url, то импортируем файл из него
		if ($url) {
			$import = $this->importMenu($url);
		} //Если url не указан, автоматически генерируем имена файлов на текущую и следующую недели
		else {
			$import1 = $this->importMenu(DinnerImportMenuComponent::getFileUrlByWeek());
			$import2 = $this->importMenu(DinnerImportMenuComponent::getFileUrlByWeek(1));

			//Загружены ли оба файла успешно
			$import = $import1 && $import2;
		}

		//Если импорт прошел удачно, отправляем напоминание на почту
		if ($import) {
			MailSender::sendDinnerReminders();
		}
	}

	/**
	 * Импортирует файл меню с помощью метода importMenu компонента DinnerImportMenu
	 * если файл импортирован успешно, выводит сообщение об этом, иначе - сообщение об ошибке
	 *
	 * @param $url url файла
	 *
	 * @return bool Если файл импортирован успешно - true, иначе - false
	 */
	private function importMenu($url)
	{
		$import = DinnerImportMenuComponent::importMenu($url);

		if ($import['status'] == 'ok') {
			$this->info($import['message']);

			return true;
		} else {
			$this->error($import['message']);

			return false;
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
			array('url', InputArgument::OPTIONAL, 'URL файла меню.', false),
		);
	}

}
