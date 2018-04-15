<?php

namespace yii2lab\domain\filters\iterator;

use yii2lab\designPattern\scenario\base\BaseScenario;
use yii2lab\domain\data\Query;
use yii2lab\helpers\yii\ArrayHelper;

class Where extends BaseScenario {

	public $query;
	
	public function run() {
		$collection = $this->getData();
		$collection = $this->filterWhere($collection, $this->query);
		$this->setData($collection);
	}
	
	protected function filterWhere(Array $collection, Query $query) {
		$condition = [];
		$where = $query->getParam('where');
		if(empty($where)) {
			return $collection;
		}
		foreach($where as $name => $value) {
			$key = 'where.' . $name;
			if($query->hasParam($key)) {
				$condition[$name] = $query->getParam($key);
			}
		}
		$collection = ArrayHelper::findAll($collection, $condition);
		return $collection;
	}
}
