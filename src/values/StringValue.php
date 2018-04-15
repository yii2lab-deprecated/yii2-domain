<?php

namespace yii2lab\domain\values;

class StringValue extends BaseValue {
	
	public function isValid($value) {
		return is_string($value);
	}
	
}
