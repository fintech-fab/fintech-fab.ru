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

		if (!$url) {
			// TODO[kmarenov] Автоматическое формирование имен файлов на текущую и следующие недели
			$this->info('url не задан');

			return;
		}

		$import_status = DinnerImportMenuComponent::importMenu($url);

		if ($import_status === true) {
			MailSender::sendDinnerReminders();
			$this->info('Меню успешно импортировано');
		} else {
			$this->error($import_status);
			//$this->error('При импорте меню произошла ошибка');
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
