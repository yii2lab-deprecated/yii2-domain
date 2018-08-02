<?php

namespace yii2lab\domain\helpers\types;

class IntegerType extends BaseType {
	
	protected function _isValid($value, $params = null) {
		return is_numeric($value) || is_integer($value);
	}
	
	public function normalizeValue($value, $params = null) {
		$value = intval($value);
		return $value;
	}
}