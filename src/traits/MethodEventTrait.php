<?php

namespace yii2lab\domain\traits;

use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\MethodEvent;

trait MethodEventTrait {
	
	private function afterMethodTrigger($method, $request = null, $response = null) {
		$event = new MethodEvent();
		list($className, $methodName) = explode('::', $method);
		$event->activeMethod = $methodName;
		$event->query = $request;
		$event->content = $response;
		$this->trigger(EventEnum::EVENT_AFTER_METHOD, $event);
		return $event;
	}
	
}