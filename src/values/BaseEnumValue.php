<?php

namespace yii2lab\domain\values;

use yii\base\InvalidConfigException;
use yii2lab\extension\common\helpers\ReflectionHelper;
use yii2mod\helpers\ArrayHelper;

abstract class BaseEnumValue extends BaseValue {
	
	public function isValid($value) {
		$range = $this->getRangeArray();
		return in_array($value, $range);
	}
	
	protected function getRangeArray() {
		$constants = ReflectionHelper::getConstants(static::class);
		if(empty($constants)) {
			throw new InvalidConfigException('Not found constants in "EnumValue"');
		}
		$range = array_values($constants);
		return $range;
	}
	
	public function getDefault() {
		$range = $this->getRangeArray();
		return ArrayHelper::first($range);
	}
}
