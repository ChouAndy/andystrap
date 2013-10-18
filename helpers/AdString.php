<?php

class AdString
{
	public static function encrypting($string = '', $hash = 'md5')
	{
		if ($hash == 'md5') {
			return md5($string);
		} 
		if ($hash == 'sha1') {
			return sha1($string);
		} else {
			return hash($hash, $string);
		}
	}

	public static function getYesNo($value)
	{
		$alias = array(
			'1' => Yii::t('AdminModule.admin', 'Yes'),
			'0' => Yii::t('AdminModule.admin', 'No'),
		);
		return $alias[$value];
	}

	public static function is_utf8($str) 
	{
		if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$str) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$str) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$str) == true) 
		{
			return true;
		} else {
			return false;
		}
	}
}
