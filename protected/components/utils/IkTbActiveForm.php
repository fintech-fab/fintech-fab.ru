<?php

Yii::import('bootstrap.widgets.TbActiveForm');

class IkTbActiveForm extends TbActiveForm
{

	/**
	 * @param CModel|CActiveForm $oForm
	 * @param                    $sAttribute
	 * @param                    $aHtmlOptions
	 *
	 * @return string
	 */
	public function phoneMaskedRow(CModel $oForm, $sAttribute, $aHtmlOptions = array())
	{

		if (empty($aHtmlOptions['mask'])) {
			$aHtmlOptions['mask'] = '+7 999 999 99 99';
		}

		$aDefaultOptions = array(
			'size'      => 10,
			'maxlength' => 10
		);

		if (!empty($aHtmlOptions)) {
			$aDefaultOptions = array_merge($aDefaultOptions, $aHtmlOptions);
		}

		$sReturn = $this->maskedTextFieldRow($oForm, $sAttribute, $aHtmlOptions['mask'], $aDefaultOptions);

		return $sReturn;

	}


	public function fieldPassportRow(CModel $oForm, $aAttributes, $aHtmlOptions = array())
	{

		$aReturn = array();


		foreach ($aAttributes as $sAttribute) {
			if (empty($aHtmlOptions[$sAttribute])) {
				$aHtmlOptions[$sAttribute] = array();
			}

			if (empty($aHtmlOptions[$sAttribute]['hideLabel'])) {
				$aReturn[$sAttribute]['label'] = $this->label(
					$oForm,
					$sAttribute,
					array(
						'required' => $oForm->isAttributeRequired($sAttribute)
					)
				);
			}

			$aDefaultOptions = array(
				'size'      => '6',
				'maxlength' => '6',
				'mask'      => '999999',
				'class'     => 'span2'
			);

			if (!empty($aHtmlOptions[$sAttribute])) {
				$aDefaultOptions = array_merge($aDefaultOptions, $aHtmlOptions[$sAttribute]);
			} else {
				$aHtmlOptions[$sAttribute] = $aDefaultOptions;
			}

			$aReturn[$sAttribute]['field'] = $this->maskedTextField($oForm, $sAttribute, $aHtmlOptions[$sAttribute]['mask'], $aDefaultOptions);

			$aReturn[$sAttribute]['error'] = $this->error($oForm, $sAttribute);
		}

		$sReturn = '<div style="display:table;">';
		foreach ($aReturn as $sRet) {
			$sReturn .= '<div style="display:table-cell;">';
			$sReturn .= $sRet['label'];
			$sReturn .= $sRet['field'];
			$sReturn .= '</div>';
		}
		$sReturn .= '</div>';
		$sReturn .= '<div style="display:table;">';
		$sReturn .= '<div style="display:table-cell;">';
		foreach ($aReturn as $sRet) {

			$sReturn .= $sRet['error'];
		}
		$sReturn .= '</div>';
		$sReturn .= '</div>';

		return $sReturn;

	}

	/**
	 * @param CModel $oForm
	 * @param        $sAttribute
	 * @param array  $aHtmlOptions
	 *
	 * @return string
	 */

	public function fieldMaskedRow(CModel $oForm, $sAttribute, $aHtmlOptions = array())
	{

		if (empty($aHtmlOptions['mask'])) {
			$aHtmlOptions['mask'] = '+7 999 999 99 99';
		}


		$aDefaultOptions = array(
			'size'      => 16,
			'maxlength' => 16
		);

		if (!empty($aHtmlOptions)) {
			$aDefaultOptions = array_merge($aDefaultOptions, $aHtmlOptions);
		}

		$sReturn = $this->maskedTextFieldRow($oForm, $sAttribute, $aHtmlOptions['mask'], $aDefaultOptions);

		return $sReturn;

	}

	/**
	 * @param CModel|CActiveForm $oForm
	 * @param                    $sAttribute
	 * @param                    $aHtmlOptions
	 *
	 * @return string
	 */
	public function dateMaskedRow(CModel $oForm, $sAttribute, $aHtmlOptions = array())
	{

		if (empty($aHtmlOptions['mask'])) {
			$aHtmlOptions['mask'] = '99.99.9999';
		}

		$aDefaultOptions = array(
			'size'      => 10,
			'maxlength' => 10,
		);

		if (!empty($aHtmlOptions)) {
			$aDefaultOptions = array_merge($aDefaultOptions, $aHtmlOptions);
		}

		$sReturn = $this->maskedTextFieldRow($oForm, $sAttribute, $aHtmlOptions['mask'], $aDefaultOptions);

		return $sReturn;

	}

	public function regionHorizFormSelect2Row(CModel $oForm, $sAttribute, $aHtmlOptions = array())
	{
		$sReturn = '<div class="control-group">';

		if (empty($aHtmlOptions['hideLabel'])) {
			$sReturn .= $this->label(
				$oForm,
				$sAttribute,
				array(
					'required' => $oForm->isAttributeRequired($sAttribute),
					"class"    => 'control-label',
				)
			);
		}

		$aDefaultOptions = array(
			'class' => 'span3',
		);

		if (!empty($aHtmlOptions)) {
			$aDefaultOptions = array_merge($aDefaultOptions, $aHtmlOptions);
		}

		$sReturn .= '<div class="controls">' .
			$this->widget('bootstrap.widgets.TbSelect2', array(
					'asDropDownList' => true,
					'model'          => $oForm,
					'attribute'      => $sAttribute,
					'data'           => Dictionaries::getRegions(),
					'options'        => $aDefaultOptions,
				), true
			);

		$sReturn .=
			$this->error($oForm, $sAttribute) .
			'</div></div>';

		return $sReturn;
	}

	public function dateFieldRow(CModel $oForm, $sAttribute, $aHtmlOptions = array())
	{
		$sReturn = '';

		if (empty($aHtmlOptions['hideLabel'])) {
			$sReturn .= $this->label(
				$oForm,
				$sAttribute,
				array(
					'required' => $oForm->isAttributeRequired($sAttribute)
				)
			);
		}

		$aDefaultOptions = array();

		if (!empty($aHtmlOptions)) {
			$aDefaultOptions = array_merge($aDefaultOptions, $aHtmlOptions);
		}

		return
			$sReturn .
			$this->dateField($oForm, $sAttribute, $aDefaultOptions) .
			$this->error($oForm, $sAttribute);

	}


	public function fieldCombineDaysHours(CModel $oForm, $sAttribute, $sLabel, $aHtmlOptions = array())
	{

		$sReturn = '';
		if (empty($aHtmlOptions['hideLabel'])) {
			$sReturn .= $this->label(
				$oForm,
				$sAttribute,
				array(
					'class'    => 'span2',
					'label'    => $sLabel,
					'required' => $oForm->isAttributeRequired($sAttribute . '_days'),
				)
			);
		}

		$aDays = array();
		$i = 0;
		while ($i <= 180) {
			$aDays[$i] = $i . ' д.';
			$i++;
		}

		$aHours = array();
		$i = 0;
		while ($i <= 23) {
			$aHours[$i] = $i . ' ч.';
			$i++;
		}

		$sReturn .= '<span class="span2">';
		$sReturn .= $this->dropDownList($oForm, $sAttribute . '_days', $aDays, array('class' => 'span1'));
		$sReturn .= '&nbsp;';
		$sReturn .= $this->dropDownList($oForm, $sAttribute . '_hours', $aHours, array('class' => 'span1'));
		$sReturn .= '</span>';
		$sReturn .= $this->error($oForm, $sAttribute . '_days');

		return $sReturn;

	}

}
