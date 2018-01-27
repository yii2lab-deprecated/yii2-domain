<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\enums\RelationEnum;
use yii2mod\helpers\ArrayHelper;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;

class RelationLoaderHelper {
	
	public static function loadRelationItem(BaseEntity $entity, $relationConfig, $relationName, $relCollection) {
		if($relationConfig['type'] == RelationEnum::ONE) {
			$viaRelationToForeign = RelationLoaderHelper::one($entity, $relationConfig, $relationName, $relCollection);
		} elseif($relationConfig['type'] == RelationEnum::MANY) {
			$viaRelationToForeign = RelationLoaderHelper::many($entity, $relationConfig, $relationName, $relCollection);
		} elseif($relationConfig['type'] == RelationEnum::MANY_TO_MANY) {
			$viaRelationToForeign = RelationLoaderHelper::manyToMany($entity, $relationConfig, $relationName, $relCollection);
		}
		return $viaRelationToForeign;
	}
	
	public static function one(BaseEntity $entity, $relationConfig, $relationName, $relCollection) {
		$fieldValue = $entity->{$relationConfig['field']};
		$entity->{$relationName} = $relCollection[$fieldValue];
		return $relationConfig;
	}
	
	public static function many(BaseEntity $entity, $relationConfig, $relationName, $relCollection) {
		$fieldValue = $entity->{$relationConfig['field']};
		$query = Query::forge();
		$query->where($relationConfig['foreign']['field'], $fieldValue);
		$entity->{$relationName} = ArrayIterator::allFromArray($query, $relCollection);
		return $relationConfig;
	}
	
	public static function manyToMany(BaseEntity $entity, $relationConfig, $relationName, $relCollection) {
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
