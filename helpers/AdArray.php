<?php
class AdArray extends TbArray
{
	public static function setValue($key, $value, $array)
	{
		if (!isset($array[$key])) {
			$array[$key] = $value;
		}
		return $array;
	}
}