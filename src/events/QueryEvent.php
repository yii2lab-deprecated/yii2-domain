<?php

namespace yii2lab\domain\events;

use yii\base\Event;
use yii2lab\domain\data\Query;

class QueryEvent extends Event {
	
	/**
	 * @var Query
	 */
	public $query;
	
}
