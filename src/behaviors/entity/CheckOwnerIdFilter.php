<?php

namespace yii2lab\domain\behaviors\entity;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\events\ReadEvent;

class CheckOwnerIdFilter extends BaseEntityFilter {
	
	public $attribute;
	
	public function prepareContent(BaseEntity $entity, ReadEvent $event) {
		if($event->type == ReadEvent::TYPE_ENTITY) {
			\App::$domain->account->auth->checkOwnerId($entity, $this->attribute);
		}
	}
	
}
