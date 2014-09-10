<?php

namespace FintechFab\ActionsCalc\Controllers;

use App;
use Controller;
use View;
use Request;
use Config;

/**
 * Class BaseController
 *
 * @package FintechFab\ActionsCalc\Controllers
 */
class BaseController extends Controller
{
	/**
	 * @var int
	 */
	protected $iTerminalId;
	/**
	 * @var string
	 */
	private $sLayoutFolderName = 'default';

	/**
	 *
	 */
	public function __construct()
	{
		$this->iTerminalId = (int)Config::get('ff-actions-calc::terminal_id');
	}

	/**
	 * Setup layout
	 */
	protected function setupLayout()
	{
		if (!is_null($this->layout)) {
			$this->layout = View::make('ff-actions-calc::layouts.' . $this->layout);
		}
	}

	/**
	 * @param       $sTemplate
	 * @param array $aParams
	 *
	 * @return $this|\Illuminate\View\View
	 */
	protected function make($sTemplate, $aParams = array())
	{
		if (Request::ajax()) {
			return $this->makePartial($sTemplate, $aParams);
		} else {
			return $this->layout->nest('content', 'ff-actions-calc::' . $sTemplate, $aParams);
		}
	}

	/**
	 * @param       $sTemplate
	 * @param array $aParams
	 *
	 * @return \Illuminate\View\View
	 */
	protected function makePartial($sTemplate, $aParams = array())
	{
		return View::make($this->sLayoutFolderName . '.' . $sTemplate, $aParams);
	}
}
