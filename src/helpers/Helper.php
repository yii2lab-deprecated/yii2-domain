<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

class Helper {
	
	public static function forgeEntity($value, string $className, bool $isCollection = null) {
		if(empty($value)) {
			return null;
		}
		if($value instanceof $className) {
			return $value;
		}
		if(!is_array($value)) {
			return null;
		}
		if(ArrayHelper::isIndexed($value)) {
			$result = [];
			foreach($value as &$item) {
				$result[] = self::forgeEntity($item, $className);
			}
		} else {
			/** @var BaseEntity $result */
			$result = new $className();
			$result->load($value);
		}
		/*if($isCollection !== null) {
			if() {
			
			}
		}*/
		return $result;
	}
	
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
	
}