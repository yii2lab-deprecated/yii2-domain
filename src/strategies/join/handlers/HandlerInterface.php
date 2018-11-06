<?php

namespace yii2lab\domain\strategies\join\handlers;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\dto\WithDto;
use yii2lab\domain\entities\relation\RelationEntity;

interface HandlerInterface {
	
	public function join(array $collection, RelationEntity $relationEntity);
	public function load(BaseEntity $entity, WithDto $w, $relCollection) : RelationEntity;
	
}
