<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\enums\RelationEnum;
use yii2lab\helpers\DomainHelper;
use yii2mod\helpers\ArrayHelper;
use yii2lab\domain\data\Query;

class JoinHelper {
	
	public static function all($collection, $relationConfig) {
		$type = $relationConfig['type'];
		if($type == RelationEnum::ONE) {
			return self::allForOne($collection, $relationConfig);
		} elseif($type == RelationEnum::MANY) {
			return self::allForMany($collection, $relationConfig);
		} elseif($type == RelationEnum::MANY_TO_MANY) {
			return self::allForManyToMany($collection, $relationConfig);
		}
		return null;
	}
	
	private static function allForOne(array $collection, array $relationConfig) {
		$query = self::forgeQuery($collection, $relationConfig);
		$relCollection = RelationRepositoryHelper::getAll($relationConfig['foreign']['domain'], $relationConfig['foreign']['name'], $query);
		$relCollection = ArrayHelper::index($relCollection, $relationConfig['foreign']['field']);
		return $relCollection;
	}
	
	private static function allForMany(array $collection, array $relationConfig) {
		$query = self::forgeQuery($collection, $relationConfig);
		$relCollection = RelationRepositoryHelper::getAll($relationConfig['foreign']['domain'], $relationConfig['foreign']['name'], $query);
		$relCollection = ArrayHelper::index($relCollection, $relationConfig['field']);
		return $relCollection;
	}
	
	private static function allForManyToMany(array $collection, array $relationConfig) {
		$viaRelations = RelationRepositoryHelper::getRelationsConfig($relationConfig['via']['domain'], $relationConfig['via']['name']);
		$viaRelationToThis = $viaRelations[$relationConfig['via']['this']];
		$ids = ArrayHelper::getColumn($collection, $viaRelationToThis['foreign']['field']);
		$query = Query::forge();
		$query->where($viaRelationToThis['field'], $ids);
		$relCollection = RelationRepositoryHelper::getAll($relationConfig['via']['domain'], $relationConfig['via']['name'], $query);
		return $relCollection;
	}
	
	private static function forgeQuery(array $collection, array $relationConfig) {
		$whereValue = self::getColumn($collection, $relationConfig['field']);
		$query = Query::forge();
		$query->where($relationConfig['foreign']['field'], $whereValue);
		return $query;
	}
	
	private static function getColumn($data, $field) {
		if(DomainHelper::isEntity($data)) {
			return $data->{$field};
		} else {
			$in = ArrayHelper::getColumn($data, $field);
			$in = array_unique($in);
			$in = array_values($in);
			return $in;
		}
	}
	
}
