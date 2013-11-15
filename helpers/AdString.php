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

	public static function is_utf8($str) 
	{
		if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$str) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$str) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$str) == true) 
		{
			return true;
		} else {
			return false;
		}
	}

	/**
     * 省略字串，並不會造成中文切字亂碼
	 *
	 * @param string 需切割字串
	 * @param int 切割剩多少字 
	 *
     * @return string ObjText
	 *
     */
	public static function getSubstr($str, $len, $encode = 'utf-8')
	{
		$resultStr = $str;
		switch ($encode) {
			case 'big5':
				for ($i = 0; $i < $len; $i++) {
					if ($i >= 0 AND $i < $len) {
						if (ord(substr($str, $i, 1)) > 0xa1) {
							$resultStr .= substr($str, $i, 2);
						} else {
							$resultStr .= substr($str, $i, 1);
						}
					}
					if (ord(substr($str, $i, 1)) > 0xa1) $i++;
				}
				if (strlen($str) <= $len)
					return $resultStr;
				else
					return $resultStr."...";
				break;
			
			case 'utf-8':
				if (mb_strlen($str, $encode) > $len) {
					$resultStr = mb_substr($str, 0, $len, $encode).'...';
				}
				return $resultStr;
				break;
		}
	}

	/**
     * use pre tag print_r $data
	 *
	 * @param want to print data 
	 *
     */
	public static function pr($data)
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
}
