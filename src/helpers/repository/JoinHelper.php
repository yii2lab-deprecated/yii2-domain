<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\entities\relation\RelationEntity;
use yii2lab\domain\enums\RelationEnum;
use yii2lab\domain\helpers\DomainHelper;
use yii2mod\helpers\ArrayHelper;
use yii2lab\domain\data\Query;

class JoinHelper {
	
	public static function all($collection, RelationEntity $relationConfig) {
		$type = $relationConfig->type;
		if(empty($collection)) {
			return null;
		}
		if($type == RelationEnum::ONE) {
			return self::allForOne($collection, $relationConfig);
		} elseif($type == RelationEnum::MANY) {
			return self::allForMany($collection, $relationConfig);
		} elseif($type == RelationEnum::MANY_TO_MANY) {
			return self::allForManyToMany($collection, $relationConfig);
		}
		return null;
	}
	
	private static function allForOne($collection, RelationEntity $relationConfig) {
		$query = self::forgeQuery($collection, $relationConfig);
		$relCollection = RelationRepositoryHelper::getAll($relationConfig->foreign, $query);
		$relCollection = ArrayHelper::index($relCollection, $relationConfig->foreign->field);
		return $relCollection;
	}
	
	private static function allForMany($collection, RelationEntity $relationConfig) {
		$query = self::forgeQuery($collection, $relationConfig);
		$relCollection = RelationRepositoryHelper::getAll($relationConfig->foreign, $query);
		return $relCollection;
	}
	
	private static function allForManyToMany($collection, RelationEntity $relationConfig) {
		/** @var RelationEntity[] $viaRelations */
		$viaRelations = RelationConfigHelper::getRelationsConfig($relationConfig->via->domain, $relationConfig->via->name);
		$name = $relationConfig->via->self;
		/** @var RelationEntity $viaRelationToThis */
		$viaRelationToThis = $viaRelations[$name];
		$ids = ArrayHelper::getColumn($collection, $viaRelationToThis->foreign->field);
		$query = Query::forge();
		$query->where($viaRelationToThis->field, $ids);
		$relCollection = RelationRepositoryHelper::getAll($relationConfig->via, $query);
		return $relCollection;
	}
	
	private static function forgeQuery($collection, RelationEntity $relationConfig) {
		$whereValue = self::getColumn($collection, $relationConfig->field);
		$query = Query::forge();
		$query->where($relationConfig->foreign->field, $whereValue);
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
