<?php

namespace yii2lab\domain\filters\iterator;

use yii2lab\designPattern\scenario\base\BaseScenario;
use yii2lab\domain\data\Query;
use yii2lab\helpers\yii\ArrayHelper;

class Sort extends BaseScenario {

	public $query;
	
	public function run() {
		$collection = $this->getData();
		$collection = $this->filterSort($collection, $this->query);
		$this->setData($collection);
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
