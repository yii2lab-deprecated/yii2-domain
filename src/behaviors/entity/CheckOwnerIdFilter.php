<?php

namespace yii2lab\domain\behaviors\entity;

use yii\web\ForbiddenHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\events\ReadEvent;

class CheckOwnerIdFilter extends BaseEntityFilter {
	
	public $attribute = 'user_id';
	
	public function prepareContent(BaseEntity $entity, ReadEvent $event) {
		if($event->type == ReadEvent::TYPE_ENTITY) {
			$currentUserId = \App::$domain->account->auth->identity->id;
			$attributeValue = $entity->{$this->attribute};
			if($attributeValue != $currentUserId) {
				throw new ForbiddenHttpException();
			}
		}
	}
	
}
