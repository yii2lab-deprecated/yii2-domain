<?php

namespace yii2lab\domain\filters\iterator;

use yii\base\BaseObject;
use yii2lab\domain\data\Query;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\designPattern\filter\interfaces\FilterInterface;

class Where extends BaseObject implements FilterInterface {

	public $query;
	
	public function run($collection) {
		$collection = $this->filterWhere($collection, $this->query);
		return $collection;
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
