<?php

namespace yii2lab\domain\behaviors\entity;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\events\ReadEvent;

class HideAttributeFilter extends BaseEntityFilter {
	
	const ACTION_HIDE = 'ACTION_HIDE';
	const ACTION_SET_NULL = 'ACTION_SET_NULL';
	
	public $secureAttributes = [];
	public $allowOnly = [];
	public $action = self::ACTION_HIDE;
	
	public function prepareContent(BaseEntity $entity, ReadEvent $event) {
		$isAllow = \App::$domain->rbac->manager->isAllow($this->allowOnly);
		if(!$isAllow) {
			$this->hideAttributes($entity);
		}
	}
	
	private function hideAttributes(BaseEntity $entity) {
		if(empty($this->secureAttributes)) {
			return;
		}
		if($this->action == self::ACTION_HIDE) {
			$entity->hideAttributes($this->secureAttributes);
		} else {
			foreach($this->secureAttributes as $attribute) {
				$entity->{$attribute} = null;
			}
		}
	}
	
}
