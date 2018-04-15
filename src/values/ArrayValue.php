<?php

namespace yii2lab\domain\values;

use yii2lab\helpers\ClassHelper;
use yii2mod\helpers\ArrayHelper;

class ArrayValue extends BaseValue {
	
	public function isValid($value) {
		return is_array($value);
	}
	
	public function updateByKey($key, $value) {
		$arrayValue = $this->get([]);
		ArrayHelper::setValue($arrayValue, $key, $value);
	}
	
	public function getByKey($key, $default = null) {
		$arrayValue = $this->get([]);
		ArrayHelper::getValue($arrayValue, $key, $default);
	}
	
	/**
	 * @param $key
	 *
	 * @return bool
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function hasByKey($key) {
		$nullValue = ClassHelper::createObject(NullValue::class);
		$value = $this->getByKey($key, $nullValue);
		if($value instanceof NullValue) {
			return false;
		}
		return true;
	}
	
}
