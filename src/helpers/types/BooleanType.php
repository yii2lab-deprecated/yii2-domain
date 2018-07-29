<?php

namespace yii2lab\domain\helpers\types;

class BooleanType extends BaseType {
	
	public function isValid($value, $params = null) {
		return is_numeric($value) || is_bool($value);
	}
	
	public function normalizeValue($value, $params = null) {
		$value = boolval($value);
		return $value;
	}
}