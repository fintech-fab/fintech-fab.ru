<?php
/**
 * Class User
 */

class User extends CWebUser
{
	/**
	 * Функция возвращает маскированный ID, т.е. телефон в формате 915xxxxx00, где "x" забивает часть цифр номера
	 *
	 * @return mixed
	 */

	public function getMaskedId()
	{
		$sMaskedId = substr_replace($this->getState('__id'),'xxxxx',3,5);


		return $sMaskedId;
	}

}