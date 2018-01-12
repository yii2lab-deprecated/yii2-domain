<?php

namespace yii2lab\domain\helpers;

use yii2mod\helpers\ArrayHelper;

class RelationWithHelper {
	
	public static function fetch($withArray, &$withTrimmedArray = []) {
		$fields = [];
		foreach($withArray as $with) {
			$dotPos = strpos($with, DOT);
			if($dotPos !== false) {
				$withTrimmed = substr($with, $dotPos + 1);
				$fieldName = substr($with, 0, $dotPos);
			} else {
				$withTrimmed = null;
				$fieldName = $with;
			}
			if(!empty($fieldName)) {
				$fields[] = $fieldName;
			}
			if(!empty($withTrimmed)) {
				$withTrimmedArray[$fieldName][] = $withTrimmed;
			} else {
				$withTrimmedArray[$fieldName] = [];
			}
			
		}
		$fields = array_unique($fields);
		return $fields;
	}
	
	public static function toMap($withArray) {
		if(!ArrayHelper::isIndexed($withArray)) {
			return $withArray;
		}
		$map = [];
		foreach($withArray as $withItem) {
			ArrayHelper::setValue($map, $withItem, []);
		}
		return $map;
	}
	
}
