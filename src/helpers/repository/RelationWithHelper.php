<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\data\Query;

class RelationWithHelper {
	
	public static function cleanWith(array $relations, Query $query) : array {
		if(!$relations) {
			return [];
		}
		$relationNames = array_keys($relations);
		$query = Query::forge($query);
		$with = $query->getParam('with');
		// todo: @deprecated удалить этот костыль при полном переходе на связи в репозитории
		$query->removeParam('with');
		if($relations && !empty($with)) {
			foreach($with as $w) {
				$w1 = self::extractName($w);
				if(!in_array($w1, $relationNames)) {
					$query->with($w1);
				}
			}
		}
		return $with ? $with : [];
	}
	
	public static function fetch($query, array &$withTrimmedArray = []) : array {
		if($query instanceof Query) {
			$withArray = $query->getParam('with');
		} elseif(is_array($query)) {
			/** @deprecated */
			$withArray = $query;
		} else {
			return [];
		}
		if(empty($withArray)) {
			return [];
		}
		$withArray = self::sortWithParam($withArray);
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
	
	private static function sortWithParam(array $withArray) : array {
		$withArray = array_unique($withArray);
		usort($withArray, 'sortByLen');
		$withArray = array_reverse($withArray);
		return $withArray;
	}
	
	private static function extractName(string $w) : string {
		$dotPos = strpos($w, DOT);
		if($dotPos !== false) {
			$w1 = substr($w, 0, $dotPos);
		} else {
			$w1 = $w;
		}
		return $w1;
	}
	
}
