<?php

namespace yii2lab\domain\values;

use yii\base\InvalidConfigException;
use yii2lab\extension\common\helpers\ReflectionHelper;
use yii2mod\helpers\ArrayHelper;

abstract class BaseMultiEnumValue extends BaseValue {
	
	public function set($value) {
		$value = array_values($value);
		parent::set($value);
	}
	
	public function add($value) {
		$value = ArrayHelper::toArray($value);
		$nativeValue = $this->get();
		if(!$this->isValid($value)) {
			$this->showInvalidException();
		}
		foreach($value as $item) {
			if(!in_array($item, $nativeValue)) {
				$nativeValue[] = $item;
			}
		}
		$this->set($nativeValue);
	}
	
	public function remove($value) {
		$value = ArrayHelper::toArray($value);
		$nativeValue = $this->get();
		if(!$this->isValid($value)) {
			$this->showInvalidException();
		}
		foreach($value as $item) {
			if(in_array($item, $nativeValue)) {
				ArrayHelper::removeValue($nativeValue, $item);
			}
		}
		$this->set($nativeValue);
	}
	
	public function isValid($value) {
		if(!is_array($value)) {
			return false;
		}
		$constants = ReflectionHelper::getConstants(static::class);
		$values = array_values($constants);
		foreach($value as $item) {
			if(!in_array($item, $values)) {
				return false;
			}
		}
		return true;
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
		return [];
	}
}
