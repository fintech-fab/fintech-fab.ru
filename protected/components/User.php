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

	/**
	 * @param mixed  $id
	 * @param string $name
	 * @param array  $states
	 */
	protected function changeIdentity($id, $name, $states)
	{
		//отключено для того, чтобы ID сессии при логине не менялся
		//Yii::app()->getSession()->regenerateID(true);
		$this->setId($id);
		$this->setName($name);
		$this->loadIdentityStates($states);
	}
}