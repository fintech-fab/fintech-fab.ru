<?php

class HttpRequest extends CHttpRequest
{
	public $aIgnoreCsrfValidationRoutes = array();

	protected function normalizeRequest()
	{
		parent::normalizeRequest();

		try {
			$route = Yii::app()->getUrlManager()->parseUrl($this);

			if (in_array($route, $this->aIgnoreCsrfValidationRoutes)) {
				Yii::app()->detachEventHandler('onBeginRequest', array($this, 'validateCsrfToken'));
			}
		} catch (CException $e) {

		}
	}
}