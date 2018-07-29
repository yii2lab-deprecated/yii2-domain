<?php

namespace yii2lab\domain\helpers\types;

class ArrayType extends BaseType {
	
	public function isValid($value, $params = null) {
		return is_array($value);
	}
	
	public function normalizeValue($value, $params = null) {
		return $value;
	}
}