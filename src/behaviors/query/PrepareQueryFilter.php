<?php

namespace yii2lab\domain\behaviors\query;

use yii\base\Behavior;
use yii2lab\domain\data\Query;
use yii2lab\domain\events\QueryEvent;
use yii2lab\domain\services\base\BaseService;

abstract class PrepareQueryFilter extends Behavior
{
	
	abstract public function prepareQuery(Query $query);
	
	public function events()
	{
		return [
			BaseService::EVENT_PREPARE_QUERY => 'prepareQueryEvent'
		];
	}
	
	public function prepareQueryEvent(QueryEvent $event) {
		$this->prepareQuery($event->query);
	}
	
}
