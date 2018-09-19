<?php

namespace yii2lab\domain\behaviors\query;

use yii\base\Behavior;
use yii2lab\domain\data\Query;
use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\QueryEvent;

abstract class BaseQueryFilter extends Behavior
{
	
	abstract public function prepareQuery(Query $query);
	
	public function events()
	{
		return [
			EventEnum::EVENT_PREPARE_QUERY => 'prepareQueryEvent'
		];
	}
	
	public function prepareQueryEvent(QueryEvent $event) {
		$this->prepareQuery($event->query);
	}
	
}
