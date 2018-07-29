<?php

namespace yii2lab\domain\values;

use yii2module\lang\domain\helpers\LangHelper;

class LangValue extends BaseValue {
	
	protected function _encode($value) {
		if(empty($value)) {
			return '';
		}
		return LangHelper::extract($value);
	}
	
	public function isValid($value) {
		if(empty($value)) {
			return true;
		}
		return is_string($value) || is_array($value);
	}
	
}
