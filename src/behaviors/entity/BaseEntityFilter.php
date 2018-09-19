<?php

namespace yii2lab\domain\behaviors\entity;

use yii\base\Behavior;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\ReadEvent;

abstract class BaseEntityFilter extends Behavior
{
	
	abstract public function prepareContent(BaseEntity $entity);
	
	public function events()
	{
		return [
			EventEnum::EVENT_AFTER_READ => 'afterReadEvent'
		];
	}
	
	public function afterReadEvent(ReadEvent $event) {
		if($event->content instanceof BaseEntity) {
			$this->prepareContent($event->content);
		} else {
			foreach($event->content as $entity) {
				$this->prepareContent($entity);
			}
		}
	}
	
}
