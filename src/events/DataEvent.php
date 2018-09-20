<?php

namespace yii2lab\domain\events;

use yii\base\Event;

class DataEvent extends Event {
	
	public $request;
	public $result;
	
}
