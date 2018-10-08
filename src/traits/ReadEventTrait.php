<?php

namespace yii2lab\domain\traits;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\enums\ActiveMethodEnum;
use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\QueryEvent;
use yii2lab\domain\events\ReadEvent;

trait ReadEventTrait {
	
	
	// todo: move method in helper
	
	/**
	 * @param null $query
	 *
	 * @return null|Query
	 *
	 * @deprecated move to Query::forge()
	 */
	public function forgeQuery($query = null) {
		return Query::forge($query);
	}
	
	protected function prepareQuery(Query $query = null) {
		$action = ActiveMethodEnum::READ_ALL;
		$query = Query::forge($query);
		$event = new QueryEvent();
		$event->query = $query;
		$event->activeMethod = $action;
		$this->trigger(EventEnum::EVENT_PREPARE_QUERY, $event);
		return $query;
	}
	
	protected function afterReadTrigger($content, Query $query = null) {
		$query = Query::forge($query);
		$event = new ReadEvent();
		$event->content = $content;
		$event->query = $query;
		$event->activeMethod = $content instanceof BaseEntity ? ActiveMethodEnum::READ_ONE : ActiveMethodEnum::READ_ALL;
		$this->trigger(EventEnum::EVENT_AFTER_READ, $event);
		return $event->content;
	}
	
	
}