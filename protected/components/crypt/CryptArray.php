<?php

class CryptArray
{

	const NAME_ALGORITHM = MCRYPT_RIJNDAEL_256;
	const MCRYPT_MODE = MCRYPT_MODE_CBC;


	public static function encrypt($array, $salt = null)
	{

		$sSource = self::serialize($array);

		return self::encryptVal($sSource, $salt);

	}

	public static function decrypt($string, $salt = null)
	{

		$decrypted = self::decryptVal($string, $salt);

		return self::deserialize(trim($decrypted));

	}


	private static function serialize($aSource)
	{
		return implode('|', $aSource);
	}


	private static function deserialize($sSource)
	{
		return explode('|', $sSource);
	}


	public static function generateNewIv($salt)
	{

		$iv_size = mcrypt_get_iv_size(self::NAME_ALGORITHM, self::MCRYPT_MODE);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

		return file_put_contents(self::getIvPath($salt), $iv);

	}

	public static function getIvPath($salt)
	{
		return dirname(__FILE__) . '/iv_' . md5($salt);
	}

	public static function getIv($salt)
	{
		static $iv = null;
		if (null === $iv) {
			$iv = @file_get_contents(self::getIvPath($salt));
			if (!$iv) {
				self::generateNewIv($salt);
				$iv = file_get_contents(self::getIvPath($salt));
			}
		}

		return $iv;
	}


	public static function encryptVal($string, $salt = null)
	{
		$salt = ($salt) ? $salt : self::getSalt();
		$encrypted = mcrypt_encrypt(self::NAME_ALGORITHM, $salt, $string, self::MCRYPT_MODE, self::getIv($salt));

		return self::_base64_url_encode($encrypted);

	}

	public static function decryptVal($string, $salt = null)
	{
		$salt = ($salt) ? $salt : self::getSalt();
		$string = str_replace('%2C', ',', $string);
		$string = self::_base64_url_decode($string);
		$decrypted = mcrypt_decrypt(self::NAME_ALGORITHM, $salt, $string, self::MCRYPT_MODE, self::getIv($salt));

		return trim($decrypted);

	}

	private static function _base64_url_encode($input)
	{
		return strtr(base64_encode($input), '+/=', '-_,');
	}

	private static function _base64_url_decode($input)
	{

		$input = str_replace('%2C', ',', $input);

		return base64_decode(strtr($input, '-_,', '+/='));

	}

	protected function getSalt()
	{
		$salt = (!empty(Yii::app()->params['cryptSalt']))
			? substr(Yii::app()->params['cryptSalt'],0,32)
			: 'fkf4a7h0853k6nxlsg84';
		return $salt;
	}

}