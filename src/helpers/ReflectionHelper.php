<?php

namespace yii2lab\domain\helpers;

use ReflectionClass;

class ReflectionHelper {
	
	public static function getConstantsByPrefix($class, $prefix) {
		$constants = self::getConstants($class);
		return self::filterByPrefix($constants, $prefix);
	}
	
	public static function getConstantsValuesByPrefix($class, $prefix) {
		$constants = self::getConstantsByPrefix($class, $prefix);
		return array_values($constants);
	}
	
	public static function getConstantsNamesByPrefix($class, $prefix) {
		$constants = self::getConstantsByPrefix($class, $prefix);
		return array_keys($constants);
	}
	
	public static function getConstants($class) {
		$class = new ReflectionClass($class);
		$constants = $class->getConstants();
		return $constants;
	}
	
	private static function filterByPrefix($constants, $prefix) {
		$result = [];
		foreach($constants as $name => $value) {
			if(self::isNameWithPrefix($prefix, $name)) {
				$result[ $name ] = $value;
			}
		}
		return $result;
	}
	
	private static function isNameWithPrefix($prefix, $name) {
		$ucPrefix = strtoupper($prefix);
		$ucPrefixWithBl = $ucPrefix . BL;
		return strpos($name, $ucPrefixWithBl) === 0;
	}
	
}