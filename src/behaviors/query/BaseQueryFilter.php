<?php

namespace yii2lab\domain\behaviors\query;

use yii\base\Behavior;
use yii2lab\domain\data\Query;
use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\QueryEvent;

abstract class BaseQueryFilter extends Behavior
{
	
	public $callback;
	
	abstract public function prepareQuery(Query $query);
	
	public function events()
	{
		return [
			EventEnum::EVENT_PREPARE_QUERY => 'prepareQueryEvent'
		];
	}
	
	public function prepareQueryEvent(QueryEvent $event) {
		if($this->callback && is_callable($this->callback)) {
			call_user_func($this->callback, $event->query);
		} else {
			$this->prepareQuery($event->query);
		}
	}
	
}
