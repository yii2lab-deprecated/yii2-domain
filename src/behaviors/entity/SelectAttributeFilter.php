<?php

namespace yii2lab\domain\behaviors\entity;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\events\ReadEvent;

class SelectAttributeFilter extends BaseEntityFilter {
	
	public function prepareContent(BaseEntity $entity, ReadEvent $event) {
		$attributes = $event->query->getParam(Query::SELECT);
		if(empty($attributes)) {
			return;
		}
		$hideAttributes = array_diff($entity->attributes(), $attributes);
		$entity->hideAttributes($hideAttributes);
	}
	
}
