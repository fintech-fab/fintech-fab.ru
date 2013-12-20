<?php
Yii::import('bootstrap.widgets.input.TbInputHorizontal');
/**
 * Class IkTbInput
 */
class IkTbInputHorizontal extends TbInputHorizontal
{
	/**
	 * * Вывод дроплиста с append и prepend (стандартный maskedTextField не добавляет append/prepend)
	 *
	 * @return string|void
	 */

	protected function dropDownList()
	{
		echo $this->getLabel();
		echo '<div class="controls">';
		echo $this->getPrepend();
		echo $this->form->dropDownList($this->model, $this->attribute, $this->data, $this->htmlOptions);
		echo $this->getAppend();
		echo $this->getError() . $this->getHint();
		echo '</div>';
	}
}