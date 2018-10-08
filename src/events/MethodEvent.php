<?php

namespace yii2lab\domain\events;

use yii\base\Event;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\extension\arrayTools\helpers\Collection;

class MethodEvent extends Event {
	
	/**
	 * @var BaseEntity|Collection|array
	 */
	public $content;
	
	/**
	 * @var Query
	 */
	public $query;
	public $activeMethod;
	
}
