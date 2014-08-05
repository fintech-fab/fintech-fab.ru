<?php

class DocumentComponent extends CComponent
{

	/**
	 * @var mPDF
	 */
	private $oMPDF;

	public function init()
	{
	}

	/**
	 * Инициализация mPdf
	 *
	 * @return mPDF
	 */
	public function mPdfInit()
	{
		$this->oMPDF = new mPDF('utf-8', 'A4', 10, '', 26, 16, 22, 16, 10, 10);

		return $this->oMPDF;
	}

	/**
	 * Генерируем PDF документ
	 */
	public function generatePDF($sView, $aParams)
	{
		if (!is_object($this->oMPDF)) {
			$this->mPdfInit();
		}

		ob_start();
		require(Yii::getPathOfAlias('application.components.views') . '/' . $sView . '.php');
		$sHtml = ob_get_clean();

		//Формирует докумнет
		$this->oMPDF->WriteHTML($sHtml);

	}

	/**
	 * Сохраняем PDF в файл
	 *
	 * @param $sFileName
	 */
	public function savePDFToFile($sFileName)
	{
		$this->oMPDF->Output($this->getFilePath($sFileName), 'F');
	}

	/**
	 * Получить путь в файлу
	 *
	 * @param $sFileName
	 *
	 * @return string
	 */
	public function getFilePath($sFileName)
	{
		if (Yii::app()->params['sDocumentsPath']) {
			$sDirectory = Yii::app()->params['sDocumentsPath'];
		} else {
			$sDirectory = dirname(__FILE__);
		}

		return realpath($sDirectory) . '/' . Yii::app()->user->getId() . '-' . $sFileName;
	}

	public function getMPDF()
	{
		if (!is_object($this->oMPDF)) {
			$this->mPdfInit();
		}

		return $this->oMPDF;
	}
}