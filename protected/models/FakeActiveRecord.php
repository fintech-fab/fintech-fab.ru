?<?php
/**
 * Class FakeActiveRecord
 */
class FakeActiveRecord
{
	public $isNewRecord = false;
	public $primaryKey = 'myid';
	public $myid = 1;
	public $myattr = 'test';

	/**
	 * @return bool
	 */
	public function isAttributeSafe()
	{
		return true;
	}

	/**
	 * @return string
	 */
	public function getAttributeLabel()
	{
		return 'Город';
	}

	/**
	 * @param $attr
	 *
	 * @return mixed
	 */

	public function getAttribute($attr)
	{
		return $this->$attr;
	}
}