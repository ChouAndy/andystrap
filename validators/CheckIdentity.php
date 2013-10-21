<?php

class CheckIdentity extends CValidator
{
	public $allowEmpty = false;

	protected function validateAttribute($object, $attribute)
	{
		$valid = false;
		$identity = $object->$attribute;

		if($this->allowEmpty && $this->isEmpty($identity)) {
			return;
		} else {
			if (preg_match("/^[a-zA-Z][12][0-9]{8}$/", $identity, $r)) {
				$x = 10 + strpos("abcdefghjklmnpqrstuvxywzio", strtolower($identity[0]));
				$chksum = ($x - ($x % 10)) / 10 + ($x % 10) * 9;
				for ($i = 1; $i < 9; $i++)
				{
					$chksum += $identity[$i] * (9 - $i);
				}
				$chksum = (10 - $chksum % 10) % 10;
				if ($chksum == $identity[9])
				{
					$valid = true;
				}
			}

			$message = $this->message !== null ? $this->message : 'Identity is incorrect.';

			if (!$valid) {
				$this->addError($object, $attribute, $message);
			}
		}
	}
}