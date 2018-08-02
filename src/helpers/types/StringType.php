<?php

namespace yii2lab\domain\helpers\types;

class StringType extends BaseType {
	
	protected function _isValid($value, $params = null) {
		return is_string($value) || is_numeric($value);
	}
	
	public function normalizeValue($value, $params = null) {
		$value = strval($value);
		return $value;
	}
}