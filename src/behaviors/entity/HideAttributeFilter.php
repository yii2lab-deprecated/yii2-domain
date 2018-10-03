<?php

namespace yii2lab\domain\behaviors\entity;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\events\ReadEvent;
use yii2lab\domain\traits\behavior\AllowOnlyTrait;

class HideAttributeFilter extends BaseEntityFilter {
	
	use AllowOnlyTrait;
	
	const ACTION_HIDE = 'ACTION_HIDE';
	const ACTION_SET_NULL = 'ACTION_SET_NULL';
	
	public $secureAttributes = [];
	public $action = self::ACTION_HIDE;
	
	public function prepareContent(BaseEntity $entity, ReadEvent $event) {
		if($this->isAllow()) {
			return;
		}
		$this->hideAttributes($entity);
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
