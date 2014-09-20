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
		// лучше сделать по умолчанию урл, т.к. команду скачивания и парсинга хочется сделать автоматической,
		// а не запускать руками.
		// файлы у них более-менее по внятным ссылкам:
		// http://www.obedvofis.info/images/files_menu/08.09%20%20-%2012.09.xls
		// http://www.obedvofis.info/images/files_menu/15.09%20-%2019.09.xls
		// вполне можно поставить на автомат, исходя из текущей даты - забирать файл на текущую и следующую неделю.
		// но если вдруг автомат не нашел файла - то надо бы сообщить об этом на емейл "администратора" (указывается в конфиге)
		// но при этом оставить возможность задать файл вручную.
		$url = $this->argument('url');

		if (DinnerImportMenuComponent::importMenu($url)) {
			MailSender::sendDinnerReminders();
			$this->info('Меню успешно импортировано');
		} else {
			$this->error('При импорте меню произошла ошибка');
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
			array('url', InputArgument::REQUIRED, 'URL файла меню.'),
		);
	}

}
