<?php
Yii::import('bootstrap.widgets.input.TbInputInline');

/**
 * Class IkTbInputInline
 */
class IkTbInputInline extends TbInputInline
{
	/**
	 * @return string the rendered content
	 */
	public function init()
	{
		// для inline-типа активирует обработку ошибок, нужно для ajax-валидации

		$this->form->error(
			$this->model,
			$this->attribute,
			$this->errorOptions,
			$this->enableAjaxValidation,
			$this->enableClientValidation
		);

		return parent::init();
	}

	protected function setPlaceholder()
	{
		if (isset($this->htmlOptions['placeholder']) && $this->htmlOptions['placeholder'] == false) {
			return;
		}

		if (empty($this->htmlOptions['placeholder'])) {
			$this->htmlOptions['placeholder'] = $this->model->getAttributeLabel($this->attribute);
		}
	}

}