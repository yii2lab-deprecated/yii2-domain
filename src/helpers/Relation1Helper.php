<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii2mod\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\domain\enums\RelationEnum;

class Relation1Helper {
	
	public static function load($domain, $id, $with, $entity) {
		$relations = self::getRepositoryRelationsConfig($domain, $id);
		$map = self::withArrToMap($with);
		foreach($map as $relationName => $mapData) {
			$relationConfig = $relations[$relationName];
			$entity = self::loadRelation($entity, $relationConfig, $relationName, $mapData);
		}
		return $entity;
	}
	
	private static function withArrToMap($with) {
		if(!ArrayHelper::isIndexed($with)) {
			return $with;
		}
		$map = [];
		foreach($with as $withItem) {
			ArrayHelper::setValue($map, $withItem, []);
		}
		return $map;
	}
	
	private static function getRepositoryRelationsConfig($domain, $id) {
		$repository = self::getRepositoryInstance($domain, $id);
		$relations =  $repository->relations();
		$relations = self::normalizeConfig($relations);
		return $relations;
	}
	
	private static function getRelationCollection($data, $relationConfig) {
		$pkList = ArrayHelper::getColumn($data, $relationConfig['field']);
		$pkList = array_unique($pkList);
		$repository = self::getRepositoryInstance($relationConfig['foreign']['domain'], $relationConfig['foreign']['name']);
		$q = Query::forge();
		$q->where($relationConfig['foreign']['field'], $pkList);
		$relCollection = $repository->all($q);
		if($relationConfig['type'] == RelationEnum::ONE) {
			$relCollection = ArrayHelper::index($relCollection, $relationConfig['foreign']['field']);
		} else {
			$relCollection = ArrayHelper::index($relCollection, $relationConfig['field']);
		}
		return $relCollection;
	}
	
	private static function loadRelation($data, $relationConfig, $relationName, $mapData) {
		if($data instanceof BaseEntity) {
			$data->{$relationName} = self::getRelationData($relationConfig['foreign']['domain'], $relationConfig['foreign']['name'], $data, $relationConfig);
			if(!empty($mapData)) {
				$data->{$relationName} = self::load($relationConfig['foreign']['domain'], $relationConfig['foreign']['name'], $mapData, $data->{$relationName});
			}
		} else {
			$relCollection = self::getRelationCollection($data, $relationConfig);
			foreach($data as &$item) {
				$item = self::loadRelationItem($item, $relationConfig, $relationName, $mapData, $relCollection);
			}
		}
		return $data;
	}
	
	private static function loadRelationItem($item, $relationConfig, $relationName, $mapData, $relCollection) {
		$fieldValue = $item->{$relationConfig['field']};
		if($relationConfig['type'] == RelationEnum::ONE) {
			$item->{$relationName} = $relCollection[$fieldValue];
		} else {
			$qu = Query::forge();
			$qu->where($relationConfig['foreign']['field'], $fieldValue);
			$item->{$relationName} = ArrayIterator::allFromArray($qu, $relCollection);
			if(!empty($mapData)) {
				$item->{$relationName} = self::load($relationConfig['foreign']['domain'], $relationConfig['foreign']['name'], $mapData, $item->{$relationName});
			}
		}
		return $item;
	}
	
	private static function getRelationData($domain, $id, $data, $relation) {
		$repository = self::getRepositoryInstance($domain, $id);
		$query = self::forgeQuery($data, $relation);
		$method = $relation['type'] == RelationEnum::MANY ? 'all' : 'one';
		return $repository->{$method}($query);
	}
	
	private static function getRepositoryInstance($domain, $id) {
		$key = $domain . '.repositories.' . $id;
		$repository = ArrayHelper::getValue(Yii::$app, $key);
		return $repository;
	}
	
	private static function forgeQuery($collection, $relation) {
		$whereValue = self::getColumn($collection, $relation);
		$query = Query::forge();
		$query->where($relation['foreign']['field'], $whereValue);
		return $query;
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
	
	private static function normalizeConfig($relations) {
		foreach($relations as &$relation) {
			if(!empty($relation['foreign']['id'])) {
				list($relation['foreign']['domain'], $relation['foreign']['name']) = explode('.', $relation['foreign']['id']);
				$type = ArrayHelper::getValue($relation, 'repository.type');
				$relation['foreign']['type'] = RelationEnum::value($type);
			}
			if(empty($relation['foreign']['field'])) {
				$relation['foreign']['field'] = 'id';
			}
		}
		return $relations;
	}
}
