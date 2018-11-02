<?php

namespace yii2lab\domain\strategies\join\handlers;

use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\dto\WithDto;
use yii2lab\domain\entities\relation\RelationEntity;
use yii2lab\domain\helpers\repository\RelationConfigHelper;
use yii2lab\domain\helpers\repository\RelationRepositoryHelper;
use yii2lab\extension\arrayTools\helpers\ArrayIterator;

class ManyToMany extends Base implements HandlerInterface {
	
	public function join(array $collection, RelationEntity $relationEntity) {
		/** @var RelationEntity[] $viaRelations */
		$viaRelations = RelationConfigHelper::getRelationsConfig($relationEntity->via->domain, $relationEntity->via->name);
		$name = $relationEntity->via->self;
		$viaRelationToThis = $viaRelations[$name];
		$values = ArrayHelper::getColumn($collection, $viaRelationToThis->foreign->field);
		$query = Query::forge();
		$query->where($viaRelationToThis->field, $values);
		$relCollection = RelationRepositoryHelper::getAll($relationEntity->via, $query);
		return $relCollection;
	}
	
	public function load(BaseEntity $entity, WithDto $w, $relCollection): RelationEntity {
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