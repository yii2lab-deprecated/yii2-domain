<?php

namespace yii2lab\domain\filters\iterator;

use yii\base\BaseObject;
use yii2lab\domain\data\Query;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\misc\interfaces\FilterInterface;

class Sort extends BaseObject implements FilterInterface {

	public $query;
	
	public function run($collection) {
		$collection = $this->filterSort($collection, $this->query);
		return $collection;
	}
	
	protected function filterSort(Array $collection, Query $query) {
		$orders = $query->getParam('order');
		if (empty($orders)) {
			return $collection;
		}
		ArrayHelper::multisort($collection, array_keys($orders), array_values($orders));
		return $collection;
	}
}
