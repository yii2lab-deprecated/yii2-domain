<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\data\Query;
use yii2mod\helpers\ArrayHelper;

class RelationWithHelper {
	
	private static function extractName($w) {
		$dotPos = strpos($w, DOT);
		if($dotPos !== false) {
			$w1 = substr($w, 0, $dotPos);
		} else {
			$w1 = $w;
		}
		return $w1;
	}
	
	public static function cleanWith($relations, Query $query = null) {
		if(!$relations) {
			return null;
		}
		$relationNames = array_keys($relations);
		$query = Query::forge($query);
		$with = $query->getParam('with');
		// todo: удалить этот костыль при полном переходе на связи в репозитории
		$query->removeParam('with');
		if($relations && !empty($with)) {
			foreach($with as $w) {
				$w1 = self::extractName($w);
				if(!in_array($w1, $relationNames)) {
					$query->with($w1);
				}
			}
		}
		return $with;
	}
	
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
