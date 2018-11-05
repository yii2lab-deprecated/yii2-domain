<?php

namespace yii2lab\domain\strategies\join\handlers;

use yii\helpers\ArrayHelper;
use yii2lab\domain\dto\WithDto;
use yii2lab\domain\helpers\DomainHelper;
use yii2lab\extension\common\helpers\PhpHelper;

abstract class Base {
	
	protected static function getColumn($data, string $field) {
		if(DomainHelper::isEntity($data)) {
			return $data->{$field};
		} else {
			$in = ArrayHelper::getColumn($data, $field);
			$in = array_unique($in);
			$in = array_values($in);
			return $in;
		}
	}
	
	protected static function prepareValue($data, WithDto $w) {
		if(ArrayHelper::isIndexed($data)) {
			foreach($data as &$item) {
				$item = self::prepareValue($item, $w);
			}
			return $data;
		}
		$value = ArrayHelper::getValue($w->relationConfig, 'foreign.value');
		if($value) {
			/*if(is_callable($value)) {
				$data = call_user_func_array($value, [$data]);
			} else {
				$data = $value;
			}*/
			$data = PhpHelper::runValue($value, [$data]);
		}
		return $data;
	}
}