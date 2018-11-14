<?php

namespace yii2lab\domain\helpers;

use yii\base\InvalidConfigException;
use yii2lab\extension\common\helpers\ClassHelper;
use yii2lab\extension\common\helpers\TypeHelper;

class EntityType {
	
	public static function encode($value, $config) {
		$result = null;
		$config = self::ensureConfig($config);
		
		if(ClassHelper::isClass($config['type'])) {
			$param = $config;
			$instanceType = TypeHelper::getInstanceType('object');
			$instanceType->validate($value, $param);
			$result = $instanceType->normalizeValue($value, $param);
			
			
		} else {
			$result = TypeHelper::encode($value, $config['type']);
		}
		return $result;
	}
	
	private static function ensureConfig($config) {
		if(empty($config)) {
			throw new InvalidConfigException('Empty "fieldType" config.');
		}
		if(!is_array($config)) {
			$config = [
				'type' => $config,
			];
		}
		if(empty($config['type'])) {
			throw new InvalidConfigException('The "type" property must be set in "fieldType".');
		}
		return $config;
	}
	
}