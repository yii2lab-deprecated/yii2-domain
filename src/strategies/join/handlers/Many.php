<?php

namespace yii2lab\domain\strategies\join\handlers;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\dto\WithDto;
use yii2lab\domain\entities\relation\RelationEntity;
use yii2lab\domain\helpers\repository\RelationRepositoryHelper;
use yii2lab\extension\arrayTools\helpers\ArrayIterator;

class Many extends Base implements HandlerInterface {
	
	public function join(array $collection, RelationEntity $relationEntity) {
		$values = self::getColumn($collection, $relationEntity->field);
		$query = Query::forge();
		$query->where($relationEntity->foreign->field, $values);
		$relCollection = RelationRepositoryHelper::getAll($relationEntity->foreign, $query);
		return $relCollection;
	}
	
	public function load(BaseEntity $entity, WithDto $w, $relCollection): RelationEntity {
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
	
}