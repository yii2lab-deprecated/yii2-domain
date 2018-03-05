<?php

namespace tests\_source\values;

use yii2lab\domain\values\BaseRangeValue;

class PercentEnumValue extends BaseRangeValue
{
	
	public function getMin() {
		return 0;
	}
	
	public function getMax() {
		return 100;
	}
	
}
