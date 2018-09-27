<?php

namespace yii2lab\domain\traits\behavior;

use yii\base\Event;

trait CallbackTrait {
	
	public $callback;
	
	protected function runCallback(Event $event) {
		$isCallback = $this->callback && is_callable($this->callback);
		if(!$isCallback) {
			return false;
		}
		call_user_func($this->callback, $event);
		return true;
	}
	
}