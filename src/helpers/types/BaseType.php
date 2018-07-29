<?php

namespace yii2lab\domain\helpers\types;

use yii\base\InvalidArgumentException;

abstract class BaseType {
	
	abstract public function isValid($value, $params = null);
	abstract public function normalizeValue($value, $params = null);
	
	public function validate($value, $params = null) {
		if(!$this->isValid($value, $params)) {
			$class = basename(static::class);
			throw new InvalidArgumentException('Value "' . $value . '" not valid of "' . $class . '"!');
		}
	}
}