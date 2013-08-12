<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->redirect(Yii::app()->createUrl('admin/pages'));
	}

	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			$this->render('error', $error);
		}
	}

}