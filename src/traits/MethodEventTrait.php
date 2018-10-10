<?php

namespace yii2lab\domain\traits;

use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\MethodEvent;
use yii2lab\extension\common\helpers\ClassHelper;

trait MethodEventTrait {
	
	private function afterMethodTrigger($method, $request = null, $response = null) {
		$event = new MethodEvent();
		$methodName = ClassHelper::extractMethod($method);
		$event->activeMethod = $methodName;
		$event->query = $request;
		$event->content = $response;
		$this->trigger(EventEnum::EVENT_AFTER_METHOD, $event);
		return $event;
	}
	
}