<?php

namespace yii2lab\domain\behaviors\query;

use yii\base\Behavior;
use yii2lab\domain\data\Query;
use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\QueryEvent;
use yii2lab\domain\events\ReadEvent;
use yii2lab\domain\traits\behavior\CallbackTrait;

abstract class BaseResultFilter extends Behavior {
	
	use CallbackTrait;
	
	abstract public function postProcessingResult(Query $query, $content);
	
	public function events() {
		return [
			EventEnum::EVENT_AFTER_READ => 'postProcessingResultEvent',
		];
	}
	
	public function postProcessingResultEvent(ReadEvent $event) {
		if(!$this->runCallback($event)) {
			$this->postProcessingResult($event->query, $event->content);
		}
	}
	
}
