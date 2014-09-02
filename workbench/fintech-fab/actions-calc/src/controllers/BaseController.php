<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use View;
use Request;
use Config;

class BaseController extends Controller
{
	/**
	 * @var mixed
	 */
	protected $iTerminalId;
	/**
	 * @var string
	 */
	private $sLayoutFolderName = 'default';

	public function __construct()
	{
		$this->iTerminalId = Config::get('ff-actions-calc::terminal_id');
	}

	/**
	 * Setup layout
	 */
	protected function setupLayout()
	{
		if (!is_null($this->layout)) {
			$this->sLayoutFolderName = $this->layout;
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
			return $this->layout->nest('content', $this->sLayoutFolderName . '.' . $sTemplate, $aParams);
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
