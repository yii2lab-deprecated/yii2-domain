<?php

namespace yii2lab\domain\enums;

use yii2lab\extension\enum\base\BaseEnum;

class EventEnum extends BaseEnum {
	
	const EVENT_PREPARE_QUERY = 'EVENT_PREPARE_QUERY';
	const EVENT_AFTER_READ = 'EVENT_AFTER_READ';
	const EVENT_AFTER_METHOD = 'EVENT_AFTER_METHOD';
	
}
