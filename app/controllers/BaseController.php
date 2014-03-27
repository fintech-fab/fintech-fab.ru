<?php

namespace App\Controllers;

use Controller;
use Request;
use URL;
use View;

class BaseController extends Controller
{

	private $sLayoutFolderName = 'default';

	protected function setupLayout()
	{
		if (!is_null($this->layout)) {
			$this->sLayoutFolderName = $this->layout;
			$this->layout = View::make('layouts.' . $this->layout);
		}
	}

	protected function make($sTemplate, $aParams = array())
	{
		if (Request::ajax()) {
			return $this->makePartial($sTemplate, $aParams);
		} else {
			return $this->layout->nest('content', $this->sLayoutFolderName . '.' . $sTemplate, $aParams);
		}
	}

	protected function makePartial($sTemplate, $aParams = array())
	{
		return View::make($this->sLayoutFolderName . '.' . $sTemplate, $aParams);
	}


	protected function getRedirectBackUrl($default = null)
	{
		$url = URL::previous();
		if (!$url && $default) {
			$url = $default;
		}
		if (!$url) {
			$url = '/';
		}

		return $url;
	}

}