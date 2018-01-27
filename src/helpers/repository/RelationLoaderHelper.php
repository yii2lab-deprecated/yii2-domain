<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\enums\RelationEnum;
use yii2mod\helpers\ArrayHelper;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;

class RelationLoaderHelper {
	
	public static function loadRelationItem(BaseEntity $entity, $relationConfig, $relationName, $relCollection) {
		$viaRelationToForeign = null;
		if($relationConfig['type'] == RelationEnum::ONE) {
			$viaRelationToForeign = self::one($entity, $relationConfig, $relationName, $relCollection);
		} elseif($relationConfig['type'] == RelationEnum::MANY) {
			$viaRelationToForeign = self::many($entity, $relationConfig, $relationName, $relCollection);
		} elseif($relationConfig['type'] == RelationEnum::MANY_TO_MANY) {
			$viaRelationToForeign = self::manyToMany($entity, $relationConfig, $relationName, $relCollection);
		}
		return $viaRelationToForeign;
	}
	
	private static function one(BaseEntity $entity, $relationConfig, $relationName, $relCollection) {
		$fieldValue = $entity->{$relationConfig['field']};
		$entity->{$relationName} = $relCollection[$fieldValue];
		return $relationConfig;
	}
	
	private static function many(BaseEntity $entity, $relationConfig, $relationName, $relCollection) {
		$fieldValue = $entity->{$relationConfig['field']};
		$query = Query::forge();
		$query->where($relationConfig['foreign']['field'], $fieldValue);
		$entity->{$relationName} = ArrayIterator::allFromArray($query, $relCollection);
		return $relationConfig;
	}
	
	private static function manyToMany(BaseEntity $entity, $relationConfig, $relationName, $relCollection) {
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
