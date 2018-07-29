<?php

namespace yii2lab\domain\helpers\types;

use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Collection;
use yii2lab\domain\data\EntityCollection;
use yii2lab\domain\values\BaseValue;

class ObjectType extends BaseType {
	
	public function isValid($value, $config = null) {
		if ($value === null) {
			return true;
		}
		$class = $config['type'];
		$isCollection = !empty($config['isCollection']);
		if(is_object($value)) {
			if($value instanceof Collection && !$isCollection) {
				throw new InvalidArgumentException('Value can not be collection');
			}
			if($isCollection && !$value instanceof Collection) {
				throw new InvalidArgumentException('Need collection');
			}
			if(!$value instanceof $class) {
				throw new InvalidArgumentException('Object not instance of class');
			}
		} else {
			$isEntityClassName = is_subclass_of($class, BaseEntity::class);
			if($isEntityClassName && !is_array($value)) {
				throw new InvalidArgumentException('Entity data not array or object!');
			}
		}
		return true;
	}
	
	public function normalizeValue($value, $config = null) {
		if(is_object($value)) {
			return $value;
		}
		$class = $config['type'];
		if(is_subclass_of($class, BaseValue::class)) {
			/** @var BaseValue $valueObject */
			$valueObject = new $class;
			$valueObject->set($value);
			return $valueObject;
		}
		if(is_subclass_of($class, BaseEntity::class)) {
			if(!empty($value)) {
				return self::forgeEntity($config, $value);
			}
		}
		return null;
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
		
		$class = $config['type'];
		$result = null;
		if($isCollection) {
			return new EntityCollection($class, $value);
		} else {
			$result = new $class($value);
		}
		return $result;
	}
}