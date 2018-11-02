<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\dto\WithDto;
use yii2lab\domain\entities\relation\RelationEntity;
use yii2lab\domain\enums\RelationEnum;
use yii2lab\extension\common\helpers\PhpHelper;
use yii2mod\helpers\ArrayHelper;
use yii2lab\extension\arrayTools\helpers\ArrayIterator;
use yii2lab\domain\data\Query;

class RelationLoaderHelper {
	
	public static function loadRelationItem(BaseEntity $entity, WithDto $w, $relCollection) : RelationEntity {
		$type = $w->relationConfig->type;
		if($type == RelationEnum::ONE) {
			return self::one($entity, $w, $relCollection);
		} elseif($type == RelationEnum::MANY) {
			return self::many($entity, $w, $relCollection);
		} elseif($type == RelationEnum::MANY_TO_MANY) {
			return self::manyToMany($entity, $w, $relCollection);
		}
		return null;
	}
	
	private static function prepareValue($data, WithDto $w) {
		if(ArrayHelper::isIndexed($data)) {
			foreach($data as &$item) {
				$item = self::prepareValue($item, $w);
			}
			return $data;
		}
		$value = ArrayHelper::getValue($w->relationConfig, 'foreign.value');
		if($value) {
			/*if(is_callable($value)) {
				$data = call_user_func_array($value, [$data]);
			} else {
				$data = $value;
			}*/
			$data = PhpHelper::runValue($value, [$data]);
		}
		return $data;
	}
	
	private static function one(BaseEntity $entity, WithDto $w, $relCollection) : RelationEntity {
		$fieldValue = $entity->{$w->relationConfig->field};
		if(empty($fieldValue)) {
			return $w->relationConfig;
		}
		if(array_key_exists($fieldValue, $relCollection)) {
			$data = $relCollection[$fieldValue];
			$data = self::prepareValue($data, $w);
			$entity->{$w->relationName} = $data;
		}
		return $w->relationConfig;
	}
	
	private static function many(BaseEntity $entity, WithDto $w, $relCollection) : RelationEntity {
		$fieldValue = $entity->{$w->relationConfig->field};
		if(empty($fieldValue)) {
			return $w->relationConfig;
		}
		$query = Query::forge();
		$query->where($w->relationConfig->foreign->field, $fieldValue);
		$data = ArrayIterator::allFromArray($query, $relCollection);
		$data = self::prepareValue($data, $w);
		$entity->{$w->relationName} = $data;
		return $w->relationConfig;
	}
	
	private static function manyToMany(BaseEntity $entity, WithDto $w, $relCollection) : RelationEntity {
		$viaRelations = RelationConfigHelper::getRelationsConfig($w->relationConfig->via->domain, $w->relationConfig->via->name);
		/** @var RelationEntity $viaRelationToThis */
		$viaRelationToThis = $viaRelations[$w->relationConfig->via->self];
		/** @var RelationEntity $viaRelationToForeign */
		$viaRelationToForeign = $viaRelations[$w->relationConfig->via->foreign];
		$itemValue = $entity->{$viaRelationToForeign->foreign->field};
		$viaQuery = Query::forge();
		$viaQuery->where($viaRelationToThis->field, $itemValue);
		$viaData = ArrayIterator::allFromArray($viaQuery, $relCollection);
		$foreignIds = ArrayHelper::getColumn($viaData, $viaRelationToForeign->field);
		$query = Query::forge();
		$query->where($viaRelationToForeign->foreign->field, $foreignIds);
		$data = RelationRepositoryHelper::getAll($viaRelationToForeign->foreign, $query);
		$data = self::prepareValue($data, $w);
		$entity->{$w->relationName} = $data;
		return $viaRelationToForeign;
	}
	
}
