<?php

namespace yii2lab\domain\values;

use yii2lab\extension\arrayTools\helpers\Collection;
use yii2lab\extension\arrayTools\base\BaseCollection;
use yii2lab\extension\common\helpers\ClassHelper;
use yii2mod\helpers\ArrayHelper;

class ArrayValue extends BaseValue {

    protected function _encode($value) {
        if($value === null) {
            return null;
        }
        return new Collection($value);
    }

    protected function _decode($value) {
        return $value;
    }

     public function getDefault() {
        return null;
     }

    public function isValid($value) {
        if($value === null) {
            return true;
        }
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
