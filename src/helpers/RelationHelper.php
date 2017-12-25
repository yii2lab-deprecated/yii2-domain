<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;

class RelationHelper {
	
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
				if(!in_array($w, $relationNames)) {
					$query->with($w);
				}
			}
		}
		return $with;
	}
	
	public static function one($relations, $with, $entity) {
		if(empty($relations) || empty($with) || empty($entity)) {
			return $entity;
		}
		$relations = self::normalizeConfig($relations);
		foreach($with as $withName) {
			if(isset($relations[$withName])) {
				$relation = $relations[$withName];
				$entity = self::hasOne($entity, $relation, $withName);
			}
		}
		return $entity;
	}
	
	public static function all($relations, $with, $collection) {
		if(empty($relations) || empty($with) || empty($collection)) {
			return $collection;
		}
		$relations = self::normalizeConfig($relations);
		foreach($with as $withName) {
			if(isset($relations[$withName])) {
				$relation = $relations[$withName];
				$collection = self::hasAll($collection, $relation, $withName);
			}
		}
		return $collection;
	}
	
	private static function hasOne($entity, $relation, $withName) {
		$entity->{$withName} = self::getRelationData($entity, $relation, $relation['type']); // $repository->{$relation['type']}($query);
		return $entity;
	}
	
	private static function hasAll($collection, $relation, $withName) {
		$relCollection = self::getRelationData($collection, $relation, 'all');
		foreach($collection as $entity) {
			if($relation['type'] == 'one') {
				$relCollection = ArrayHelper::index($relCollection, $relation['repository']['field']);
				if(isset($relCollection[$entity->{$relation['field']}])) {
					$entity->{$withName} = $relCollection[$entity->{$relation['field']}];
				}
			} elseif($relation['type'] == 'all') {
				$collectionForField = $entity->{$withName};
				foreach($relCollection as $relEntity) {
					if($relEntity->{$relation['repository']['field']} == $entity->{$relation['field']}) {
						$collectionForField[] = $relEntity;
					}
				}
				$entity->{$withName} = $collectionForField;
			}
		}
		return $collection;
	}
	
	private static function getRelationData($entity, $relation, $method) {
		$repository = self::getRepositoryInstance($relation);
		$query = self::forgeQuery($entity, $relation);
		return $repository->{$method}($query);
	}
	
	private static function forgeQuery($collection, $relation) {
		$whereValue = self::getColumn($collection, $relation);
		$query = Query::forge();
		$query->where($relation['repository']['field'], $whereValue);
		return $query;
	}
	
	private static function getRepositoryInstance($relation) {
		$key = $relation['repository']['domain'] . '.repositories.' . $relation['repository']['name'];
		$repository = ArrayHelper::getValue(Yii::$app, $key);
		return $repository;
	}
	
	private static function normalizeConfig($relations) {
		foreach($relations as &$relation) {
			if(!empty($relation['repository']['id'])) {
				list($relation['repository']['domain'], $relation['repository']['name']) = explode('.', $relation['repository']['id']);
			}
		}
		return $relations;
	}
	
	private static function getColumn($collection, $relation) {
		$field = $relation['field'];
		if(is_object($collection) && $collection instanceof BaseEntity) {
			return $collection->{$field};
		} else {
			$in = ArrayHelper::getColumn($collection, $field);
			$in = array_unique($in);
			$in = array_values($in);
			return $in;
		}
	}
}
