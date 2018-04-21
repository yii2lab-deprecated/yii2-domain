<?php

namespace yii2lab\domain\values;

class BoolValue extends BaseValue {
	
	protected function _encode($value) {
		return !empty($value);
	}
	
	public function getDefault() {
		return false;
	}
	
	public function isValid($value) {
		return !is_object($value) && !is_array($value);
	}
	
}
