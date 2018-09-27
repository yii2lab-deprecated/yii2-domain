<?php

namespace yii2lab\domain\behaviors\entity;

use yii\base\Behavior;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\ReadEvent;

abstract class BaseEntityFilter extends Behavior {
	
	abstract public function prepareContent(BaseEntity $entity, ReadEvent $event);
	
	public function events() {
		return [
			EventEnum::EVENT_AFTER_READ => 'prepare',
		];
	}
	
	public function prepare(ReadEvent $event) {
		if($this->isEntity($event->content)) {
			$this->prepareContent($event->content, $event);
		} else {
			foreach($event->content as $entity) {
				$this->prepareContent($entity, $event);
			}
		}
	}
	
	protected function isEntity($content) {
		return $content instanceof BaseEntity;
	}
	
}
