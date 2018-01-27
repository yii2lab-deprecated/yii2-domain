<?php

namespace yii2lab\domain\helpers;

use yii\base\InvalidConfigException;
use yii2lab\helpers\TypeHelper;

class EntityType {
	
	public static function encode($value, $config) {
		$config = self::ensureConfig($config);
		if(self::isClassName($config['type'])) {
			if ($value === null) {
				return null;
			}
			$result = self::forgeEntity($config, $value);
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
	
	private static function isClassName($type) {
		return strpos($type, '\\') !== false;
	}
	
	private static function forgeEntity($config, $value) {
		$type = $config['type'];
		$result = null;
		if(!empty($config['isCollection'])) {
			if(empty($value)) {
				return null;
			}
			foreach($value as $item) {
				$result[] = new $type($item);
			}
		} else {
			$result = new $type($value);
		}
		return $result;
	}
	
}