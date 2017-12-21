<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

class Helper {
	
	public static function validateForm($form, $data = null, $scenario = null) {
		if(is_string($form) || is_array($form)) {
			$form = Yii::createObject($form);
		}
		/** @var \yii\base\Model $form */
		if(!empty($data)) {
			Yii::configure($form, $data);
		}
		if(!empty($scenario)) {
			$form->scenario = $scenario;
		}
		if(!$form->validate()) {
			throw new UnprocessableEntityHttpException($form);
		}
		return $form->getAttributes();
	}
	
	public static function getInstanceOfClassName($class, $classname) {
		$class = self::getClassName($class, $classname);
		if(empty($class)) {
			return null;
		}
		if(class_exists($class)) {
			return new $class();
		}
		return null;
	}
	
	public static function getClassName($class, $classname) {
		if(empty($class)) {
			return null;
		}
		if(mb_strpos($class, '\\') === false) {
			$namespace = self::getNamespaceOfClassName($classname);
			$class = $namespace . '\\' . $class;
		}
		return $class;
	}
	
	public static function getNamespaceOfClassName($class) {
		$lastSlash = strrpos($class, '\\');
		return substr($class, 0, $lastSlash);
	}
	
	public static function toArray($value) {
		if(is_object($value) && method_exists($value, 'toArray')) {
			return $value->toArray();
		}
		if(!ArrayHelper::isIndexed($value)) {
			return $value;
		}
		foreach($value as &$item) {
			$item = self::toArray($item);
		}
		return $value;
	}
	
	public static function extractNameFromClass($class, $type) {
		$lastPos = strrpos($class, '\\');
		$name = substr($class, $lastPos + 1, 0 - strlen($type));
		return $name;
	}
	
	public static function dirLevelUp($class, $upLevel) {
		$arr = explode('\\', $class);
		for($i = 0; $i < $upLevel; $i++) {
			$arr = array_splice($arr, 0, -1);
		}
		return implode('\\', $arr);
	}
}