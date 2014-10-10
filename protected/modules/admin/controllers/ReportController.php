<?php

class ReportController extends Controller
{

	public $layout = '/layouts/row2';

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 *
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array(''),
				'users'   => array('*'),
			),
			array(
				'allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array(''),
				'users'   => array('@'),
			),
			array(
				'allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array('report'),
				'users'   => array(Yii::app()->params['adminName']),
			),
			array(
				'deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionLeadsHistory()
	{

		if (!Yii::app()->request->getIsPostRequest()) {
			$this->render('leads_history');

			Yii::app()->end();
		}

		$sStartDate = Yii::app()->request->getPost('dateFrom');
		$sEndDate = Yii::app()->request->getPost('dateTo');

		if ($sStartDate == '') {
			$sStartDate = SiteParams::EMPTY_DATE;
		}

		if ($sEndDate == '') {
			$sEndDate = date('Y-m-d', SiteParams::getTime());
		}

		$aHistory = LeadsHistory::model()->scopeDateAsc()->scopeDateBetween($sStartDate, $sEndDate)->findAll();

		$i = 0;
		$aRows = array(
			array(
				'Порядковый номер',
				'Order ID',
				'Webmaster ID',
				'Лид',
				'Цель достигнута',
				'Дата перехода по ссылке',
				'Дата достижения цели',
			),
		);
		foreach ($aHistory as $oHistory) {
			$aRows[] = array(
				++$i,
				$oHistory->id,
				$oHistory->webmaster_id,
				$oHistory->lead_name,
				$oHistory->flag_showed,
				date('d.m.Y H:i', SiteParams::strtotime($oHistory->dt_add)),
				($oHistory->dt_show != SiteParams::EMPTY_DATETIME) ? date('d.m.Y H:i', SiteParams::strtotime($oHistory->dt_show)) : '',
			);
		}

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=LeadsHistory-' . SiteParams::getDateFromDateTime(SiteParams::getTime()) . '.csv');

		$output = fopen('php://output', 'w');
		echo "\xEF\xBB\xBF"; //BOM

		foreach ($aRows as $aRow) {
			fputcsv($output, $aRow, ';');
		}

		fclose($output);

		Yii::app()->end();
	}
}