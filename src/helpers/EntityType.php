<?php

namespace yii2lab\domain\helpers;

use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Collection;
use yii2lab\domain\values\BaseValue;
use yii2lab\helpers\ClassHelper;
use yii2lab\helpers\TypeHelper;

class EntityType {
	
	public static function encode($value, $config) {
		$result = null;
		$config = self::ensureConfig($config);
		if(ClassHelper::isClass($config['type'])) {
			$result = self::encodeClass($config, $value);
		} else {
			$result = TypeHelper::encode($value, $config['type']);
		}
		return $result;
	}
	
	private static function encodeClass($config, $value) {
		if ($value === null) {
			return null;
		}
		$class = $config['type'];
		
		if(is_object($value)) {
			if($value instanceof Collection) {
				if(empty($config['isCollection'])) {
					throw new InvalidArgumentException('Value can not be collection');
				}
				// check collection items
				return $value;
			} elseif($value instanceof $class) {
				return $value;
			} else {
				throw new InvalidArgumentException('Object not instance of class');
			}
		}
		
		if(is_subclass_of($class, BaseValue::class)) {
			/** @var BaseValue $valueObject */
			$valueObject = new $class;
			$valueObject->set($value);
			return $valueObject;
		}
		if(is_subclass_of($class, BaseEntity::class)) {
			if(!is_array($value)) {
				throw new InvalidArgumentException('Entity data not array or object!');
			}
			if(!empty($value)) {
				return self::forgeEntity($config, $value);
			}
		}/* else {
			//$result = self::forgeEntity($config, $value);
		}*/
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
	
	private static function forgeEntity($config, $value) {
		$isCollection = !empty($config['isCollection']);
		$isIndexed = ArrayHelper::isIndexed($value);
		if($isCollection && !$isIndexed) {
			throw new InvalidArgumentException('Need array of items for collection');
		}
		if(!$isCollection && $isIndexed) {
			throw new InvalidArgumentException('Need array of item for entity');
		}
		
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