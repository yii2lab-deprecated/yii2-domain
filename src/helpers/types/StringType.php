<?php

namespace yii2lab\domain\helpers\types;

class StringType extends BaseType {
	
	public function isValid($value, $params = null) {
		return is_string($value) || is_numeric($value);
	}
	
	public function normalizeValue($value, $params = null) {
		$value = strval($value);
		return $value;
	}
}