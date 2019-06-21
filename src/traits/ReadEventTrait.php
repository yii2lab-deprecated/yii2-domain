<?php

namespace yii2lab\domain\traits;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\enums\ActiveMethodEnum;
use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\QueryEvent;
use yii2lab\domain\events\ReadEvent;

trait ReadEventTrait
{

	// todo: move method in helper
	/**
	 * @param null $query
	 *
	 * @return null|Query
	 *
	 * @deprecated move to Query::forge()
	 */
	public function forgeQuery($query = null)
	{
		return Query::forge($query);
	}

	protected function prepareQuery(Query $query = null)
	{
		$action = ActiveMethodEnum::READ_ALL;
		$query = Query::forge($query);
//		$this->validateQuery($query);
		$event = new QueryEvent();
		$event->query = $query;
		$event->activeMethod = $action;
		$this->trigger(EventEnum::EVENT_PREPARE_QUERY, $event);
		return $query;
	}


	private function validateQuery(Query &$query)
	{
		/** @var BaseEntity $entity */
		try{
			$entity = $this->domain->factory->entity->create($this->id);
		} catch (\Exception $e){
			return;
		}
		$fields = $entity->whiteValues();
		$validatedQuery = Query::forge();
		foreach ($fields as $field) {
			if (!empty($query->getWhere($field)))
				$validatedQuery->where($field, $query->getWhere($field));
		}
		if (!empty($query->getWith()))
			$validatedQuery->with($query->getWith());

		foreach ($entity::PAGINATION_QUERY_VALUES as $value) {
			if (!empty($query->{$value}))
				$validatedQuery->{$value}($query->{$value}) ;
		}
		$query = $validatedQuery;
	}


	protected function afterReadTrigger($content, Query $query = null)
	{
		$query = Query::forge($query);
		$event = new ReadEvent();
		$event->content = $content;
		$event->query = $query;
		$event->activeMethod = $content instanceof BaseEntity ? ActiveMethodEnum::READ_ONE : ActiveMethodEnum::READ_ALL;
		$this->trigger(EventEnum::EVENT_AFTER_READ, $event);
		return $event->content;
	}


}