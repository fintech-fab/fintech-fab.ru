<?php
Yii::import('bootstrap.widgets.input.TbInputVertical');
/**
 * Class IkTbInput
 */
class IkTbInputVertical extends TbInputVertical
{
	/**
	 * * Вывод маскированного поля с append и prepend (стандартный maskedTextField не добавляет append/prepend)
	 *
	 * @return string|void
	 */
	protected function maskedTextField()
	{
		echo $this->getPrepend();
		echo $this->form->maskedTextField($this->model, $this->attribute, $this->data, $this->htmlOptions);
		echo $this->getAppend();
	}
}