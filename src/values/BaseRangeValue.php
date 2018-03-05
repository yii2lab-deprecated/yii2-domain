<?php

namespace yii2lab\domain\values;

use yii\base\InvalidConfigException;

abstract class BaseRangeValue extends BaseValue {
	
	abstract public function getMin();
	
	abstract public function getMax();
	
	public function isValid($value) {
		if($this->getMax() === null) {
			throw new InvalidConfigException('Not set "max" in "RangeValue"');
		}
		if(!is_numeric($value)) {
			return false;
		}
		return $value >= $this->getMin() && $value <= $this->getMax();
	}
	
	public function getDefault() {
		return $this->getMin();
	}
}
