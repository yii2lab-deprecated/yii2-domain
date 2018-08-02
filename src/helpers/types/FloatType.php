<?php

namespace yii2lab\domain\helpers\types;

class FloatType extends BaseType {
	
	protected function _isValid($value, $params = null) {
		return is_numeric($value) || is_float($value);
	}
	
	public function normalizeValue($value, $params = null) {
		$value = floatval($value);
		return $value;
	}
}