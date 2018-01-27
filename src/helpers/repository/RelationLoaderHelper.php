<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\enums\RelationEnum;
use yii2mod\helpers\ArrayHelper;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;

class RelationLoaderHelper {
	
	public static function loadRelationItem(BaseEntity $entity, array $relationConfig, $relationName, $relCollection) {
		$type = $relationConfig['type'];
		if($type == RelationEnum::ONE) {
			return self::one($entity, $relationConfig, $relationName, $relCollection);
		} elseif($type == RelationEnum::MANY) {
			return self::many($entity, $relationConfig, $relationName, $relCollection);
		} elseif($type == RelationEnum::MANY_TO_MANY) {
			return self::manyToMany($entity, $relationConfig, $relationName, $relCollection);
		}
		return null;
	}
	
	private static function one(BaseEntity $entity, array $relationConfig, $relationName, $relCollection) {
		$fieldValue = $entity->{$relationConfig['field']};
		$entity->{$relationName} = $relCollection[$fieldValue];
		return $relationConfig;
	}
	
	private static function many(BaseEntity $entity, array $relationConfig, $relationName, $relCollection) {
		$fieldValue = $entity->{$relationConfig['field']};
		$query = Query::forge();
		$query->where($relationConfig['foreign']['field'], $fieldValue);
		$entity->{$relationName} = ArrayIterator::allFromArray($query, $relCollection);
		return $relationConfig;
	}
	
	private static function manyToMany(BaseEntity $entity, array $relationConfig, $relationName, $relCollection) {
		$viaRelations = RelationRepositoryHelper::getRelationsConfig($relationConfig['via']['domain'], $relationConfig['via']['name']);
		$viaRelationToThis = $viaRelations[$relationConfig['via']['this']];
		$viaRelationToForeign = $viaRelations[$relationConfig['via']['foreign']];
		$itemValue = $entity->{$viaRelationToForeign['foreign']['field']};
		$viaQuery = Query::forge();
		$viaQuery->where($viaRelationToThis['field'], $itemValue);
		$viaData = ArrayIterator::allFromArray($viaQuery, $relCollection);
		$foreignIds = ArrayHelper::getColumn($viaData, $viaRelationToForeign['field']);
		$query = Query::forge();
		$query->where($viaRelationToForeign['foreign']['field'], $foreignIds);
		$entity->{$relationName} = RelationRepositoryHelper::getAll($viaRelationToForeign['foreign']['domain'], $viaRelationToForeign['foreign']['name'], $query);
		return $viaRelationToForeign;
	}
	
}
