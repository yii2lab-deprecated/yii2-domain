<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\domain\enums\RelationEnum;

class RelationHelper {
	
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
	
	public static function load($relations, $with, $data, $method = null) {
		if(empty($relations) || empty($with) || empty($data)) {
			return $data;
		}
		if(empty($method)) {
			$method = $data instanceof BaseEntity ? 'one' : 'all';
		}
		$relations = self::normalizeConfig($relations);
		foreach($with as $withName) {
			if(isset($relations[$withName])) {
				$relation = $relations[$withName];
				$data = call_user_func_array(['self', $method], [$data, $relation, $withName]);
			}
		}
		return $data;
	}
	
	public static function one($entity, $relation, $withName) {
		$method = $relation['type'] == RelationEnum::MANY ? 'all' : 'one';
		$entity->{$withName} = self::getRelationData($entity, $relation, $method); // $repository->{$relation['type']}($query);
		return $entity;
	}
	
	public static function all($collection, $relation, $withName) {
		$relCollection = self::getRelationData($collection, $relation, 'all');
		foreach($collection as $entity) {
			if($relation['type'] == RelationEnum::ONE) {
				$relCollection = ArrayHelper::index($relCollection, $relation['foreign']['field']);
				if(isset($relCollection[$entity->{$relation['field']}])) {
					$entity->{$withName} = $relCollection[$entity->{$relation['field']}];
				}
			} elseif($relation['type'] == RelationEnum::MANY) {
				$collectionForField = $entity->{$withName};
				foreach($relCollection as $relEntity) {
					if($relEntity->{$relation['foreign']['field']} == $entity->{$relation['field']}) {
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
		$query->where($relation['foreign']['field'], $whereValue);
		return $query;
	}
	
	private static function getRepositoryInstance($relation) {
		$key = $relation['foreign']['domain'] . '.repositories.' . $relation['foreign']['name'];
		$repository = ArrayHelper::getValue(Yii::$app, $key);
		return $repository;
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
	
	// =========================================================== //
	
	public static function loadOne($domain, $id, $with, $entity) {
		$relations = self::getRepositoryRelations($domain, $id);
		$map = self::withArrToMap($with);
		foreach($map as $relationName => $mapData) {
			$relationConfig = $relations[$relationName];
			$entity = self::loadRelation($entity, $relationConfig, $relationName, $mapData);
		}
		return $entity;
	}
	
	private static function withArrToMap($with) {
		$map = [];
		foreach($with as $withItem) {
			\yii2mod\helpers\ArrayHelper::setValue($map, $withItem, []);
		}
		return $map;
	}
	
	private static function getRepositoryRelations($domain, $id) {
		$repository = self::getRepositoryInstance1($domain, $id);
		$relations =  $repository->relations();
		$relations = self::normalizeConfig($relations);
		return $relations;
	}
	
	private static function getRelationCollection($data, $relationConfig) {
		$pkList = ArrayHelper::getColumn($data, $relationConfig['field']);
		$pkList = array_unique($pkList);
		$repository = self::getRepositoryInstance1($relationConfig['foreign']['domain'], $relationConfig['foreign']['name']);
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
			$data->{$relationName} = self::getRelationData1($relationConfig['foreign']['domain'], $relationConfig['foreign']['name'], $data, $relationConfig);
			if(!empty($mapData)) {
				$data->{$relationName} = self::loadOne($relationConfig['foreign']['domain'], $relationConfig['foreign']['name'], array_keys($mapData), $data->{$relationName});
			}
		} else {
			$relCollection = self::getRelationCollection($data, $relationConfig);
			foreach($data as $item) {
				$fieldValue = $item->{$relationConfig['field']};
				if($relationConfig['type'] == RelationEnum::ONE) {
					$item->{$relationName} = $relCollection[$fieldValue];
				} else {
					$qu = Query::forge();
					$qu->where($relationConfig['foreign']['field'], $fieldValue);
					$item->{$relationName} = ArrayIterator::allFromArray($qu, $relCollection);
				}
			}
		}
		return $data;
	}
	
	private static function getRelationData1($domain, $id, $data, $relation) {
		$repository = self::getRepositoryInstance1($domain, $id);
		$query = self::forgeQuery($data, $relation);
		$method = $relation['type'] == RelationEnum::MANY ? 'all' : 'one';
		return $repository->{$method}($query);
	}
	
	private static function getRepositoryInstance1($domain, $id) {
		$key = $domain . '.repositories.' . $id;
		$repository = ArrayHelper::getValue(Yii::$app, $key);
		return $repository;
	}
	
	// =========================================================== //
	
}
