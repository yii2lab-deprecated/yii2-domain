<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\dto\WithDto;
use yii2lab\domain\enums\RelationEnum;
use yii2mod\helpers\ArrayHelper;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;

class RelationLoaderHelper {
	
	public static function loadRelationItem(BaseEntity $entity, WithDto $w, $relCollection) {
		$type = $w->relationConfig['type'];
		if($type == RelationEnum::ONE) {
			return self::one($entity, $w, $relCollection);
		} elseif($type == RelationEnum::MANY) {
			return self::many($entity, $w, $relCollection);
		} elseif($type == RelationEnum::MANY_TO_MANY) {
			return self::manyToMany($entity, $w, $relCollection);
		}
		return null;
	}
	
	private static function one(BaseEntity $entity, WithDto $w, $relCollection) {
		$fieldValue = $entity->{$w->relationConfig['field']};
		if(empty($fieldValue)) {
			return $w->relationConfig;
		}
		$entity->{$w->relationName} = $relCollection[$fieldValue];
		return $w->relationConfig;
	}
	
	private static function many(BaseEntity $entity, WithDto $w, $relCollection) {
		$fieldValue = $entity->{$w->relationConfig['field']};
		if(empty($fieldValue)) {
			return $w->relationConfig;
		}
		$query = Query::forge();
		$query->where($w->relationConfig['foreign']['field'], $fieldValue);
		$entity->{$w->relationName} = ArrayIterator::allFromArray($query, $relCollection);
		return $w->relationConfig;
	}
	
	private static function manyToMany(BaseEntity $entity, WithDto $w, $relCollection) {
		$viaRelations = RelationRepositoryHelper::getRelationsConfig($w->relationConfig['via']['domain'], $w->relationConfig['via']['name']);
		$viaRelationToThis = $viaRelations[$w->relationConfig['via']['this']];
		$viaRelationToForeign = $viaRelations[$w->relationConfig['via']['foreign']];
		$itemValue = $entity->{$viaRelationToForeign['foreign']['field']};
		$viaQuery = Query::forge();
		$viaQuery->where($viaRelationToThis['field'], $itemValue);
		$viaData = ArrayIterator::allFromArray($viaQuery, $relCollection);
		$foreignIds = ArrayHelper::getColumn($viaData, $viaRelationToForeign['field']);
		$query = Query::forge();
		$query->where($viaRelationToForeign['foreign']['field'], $foreignIds);
		$entity->{$w->relationName} = RelationRepositoryHelper::getAll($viaRelationToForeign['foreign']['domain'], $viaRelationToForeign['foreign']['name'], $query);
		return $viaRelationToForeign;
	}
	
}
