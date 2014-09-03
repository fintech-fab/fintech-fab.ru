<?php

Yii::import('bootstrap.widgets.TbActiveForm');

/**
 * Class IkTbActiveForm
 */
class IkTbActiveForm extends TbActiveForm
{
	public $bShowRequired = true;

	const INPUT_INLINE = 'application.components.utils.IkTbInputInline';

	/**
	 * Renders an HTML label for a model attribute.
	 * This method is a wrapper of {@link CHtml::activeLabelEx}.
	 * Please check {@link CHtml::activeLabelEx} for detailed information
	 * about the parameters for this method.
	 *
	 * @param CModel $model       the data model
	 * @param string $attribute   the attribute
	 * @param array  $htmlOptions additional HTML attributes.
	 *
	 * @return string the generated label tag
	 */
	public function labelEx($model, $attribute, $htmlOptions = array())
	{


		if (!$this->bShowRequired) {
			return CHtml::activeLabel($model, $attribute, $htmlOptions);
		}

		return CHtml::activeLabelEx($model, $attribute, $htmlOptions);
	}

	/**
	 *### .inputRow()
	 *
	 * Creates an input row of a specific type.
	 *
	 * This is a generic factory method. It is mainly called by various helper methods
	 *  which pass correct type definitions to it.
	 *
	 * @param string $type        the input type
	 * @param CModel $model       the data model
	 * @param string $attribute   the attribute
	 * @param array  $data        the data for list inputs
	 * @param array  $htmlOptions additional HTML attributes
	 *
	 * @return string the generated row
	 */
	public function inputRow($type, $model, $attribute, $data = null, $htmlOptions = array())
	{
		$inputClassName = $this->getInputClassName();

		/*
		 * сделано для возможности указывать в htmlOptions тип инпута
		 * т.к. разные типы рендерятся по-разному, и бывает нужно вывести инпут с другим типом, не таким,
		 * какой задан у всей формы
		 *
		 * Input classes.
		 * 'bootstrap.widgets.input.TbInputHorizontal';
		 * 'bootstrap.widgets.input.TbInputInline';
		 * 'bootstrap.widgets.input.TbInputSearch';
		 * 'bootstrap.widgets.input.TbInputVertical';
		 */
		if (isset($htmlOptions['inputType'])) {
			$inputClassName = $htmlOptions['inputType'];
		}

		ob_start();
		Yii::app()->controller->widget(
			$inputClassName,
			array(
				'type'        => $type,
				'form'        => $this,
				'model'       => $model,
				'attribute'   => $attribute,
				'data'        => $data,
				'htmlOptions' => $htmlOptions,
			)
		);
		echo "\n";

		return ob_get_clean();
	}

	/**
	 * Вывод маскированного поля с append и prepend (стандартный maskedTextField не добавляет append/prepend)
	 *
	 * @param CFormModel $model
	 * @param            $attribute
	 * @param            $mask
	 * @param array      $htmlOptions
	 *
	 * @return string
	 */
	public function maskedTextField2($model, $attribute, $mask, $htmlOptions = array())
	{
		ob_start();
		$this->widget('IkTbInputVertical', array(
			'type'        => 'maskedtextfield',
			'form'        => $this,
			'model'       => $model,
			'attribute'   => $attribute,
			'data'        => $mask,
			'htmlOptions' => $htmlOptions,
		));
		echo "\n";

		return ob_get_clean();
	}

	/**
	 * @param       $model
	 * @param       $attribute
	 * @param array $data
	 * @param array $htmlOptions
	 *
	 * @return string
	 */
	public function dropDownListRow2($model, $attribute, $data = array(), $htmlOptions = array())
	{
		ob_start();
		$this->widget('IkTbInputHorizontal', array(
			'type'        => 'dropdownlist',
			'form'        => $this,
			'model'       => $model,
			'attribute'   => $attribute,
			'data'        => $data,
			'htmlOptions' => $htmlOptions,
		));
		echo "\n";

		return ob_get_clean();
	}

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
			'maxlength' => 16
		);

		if (!empty($aHtmlOptions)) {
			$aDefaultOptions = array_merge($aDefaultOptions, $aHtmlOptions);
		}

		$sReturn = $this->maskedTextFieldRow($oForm, $sAttribute, $aHtmlOptions['mask'], $aDefaultOptions);

		return $sReturn;

	}

	/**
	 * @param CModel $oForm
	 * @param        $aAttributes
	 * @param array  $aHtmlOptions
	 *
	 * @return string
	 */
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

	/**
	 * @param CModel $oForm
	 * @param        $sAttribute
	 * @param array  $aHtmlOptions
	 *
	 * @return string
	 */
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

	/**
	 * @param CModel $oForm
	 * @param        $sAttribute
	 * @param        $sLabel
	 * @param array  $aHtmlOptions
	 *
	 * @return string
	 */
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

	/**
	 * Returns the input widget class name suitable for the form.
	 *
	 * @return string the class name
	 */
	protected function getInputClassName()
	{
		if (isset($this->input)) {
			return $this->input;
		} else {
			switch ($this->type) {
				case self::TYPE_HORIZONTAL:
					return self::INPUT_HORIZONTAL;
					break;

				case self::TYPE_INLINE:
					return self::INPUT_INLINE;
					break;

				case self::TYPE_SEARCH:
					return self::INPUT_SEARCH;
					break;

				case self::TYPE_VERTICAL:
				default:
					return self::INPUT_VERTICAL;
					break;
			}
		}
	}
}
