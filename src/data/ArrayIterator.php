<?php

namespace yii2lab\domain\data;

use yii\web\NotFoundHttpException;
use yii2lab\domain\helpers\ReflectionHelper;
use yii2lab\helpers\yii\ArrayHelper;

class ArrayIterator {

	// сохраняем порядок фильтров как есть
	const FILTER_WHERE = 'where';
	const FILTER_SORT = 'sort';
	
	protected $collection;
	
	public static function oneFromArray(Query $query = null, $array) {
		$array = !empty($array) ? $array : [];
		$iterator = new ArrayIterator();
		$iterator->setCollection($array);
		return $iterator->one($query);
	}
	
	public static function allFromArray(Query $query = null, $array) {
		$iterator = new ArrayIterator();
		$iterator->setCollection($array);
		return $iterator->all($query);
	}
	
	public function setCollection(Array $value) {
		$this->collection = $value;
	}
	
	public function one(Query $query = null, $filters = [self::FILTER_SORT, self::FILTER_WHERE]) {
		$collection = $this->runFilters($query, $filters);
		if(empty($collection) || empty($collection[0])) {
			throw new NotFoundHttpException(static::class);
		}
		return $collection[0];
	}
	
	public function all(Query $query = null, $filters = [self::FILTER_SORT, self::FILTER_WHERE]) {
		$collection = $this->runFilters($query, $filters);
		return $collection;
	}
	
	public function count(Query $query = null) {
		$collection = $this->runFilters($query, [self::FILTER_WHERE]);
		return count($collection);
	}
	
	protected function runFilters(Query $query = null, $filters = [self::FILTER_SORT, self::FILTER_WHERE]) {
		$query = Query::forge($query);
		$collection = $this->collection;
		$allFilters = ReflectionHelper::getConstantsValuesByPrefix($this,'filter');
		foreach($allFilters as $filterName) {
			if(in_array($filterName, $filters)) {
				$method = 'filter' . ucfirst($filterName);
				$collection = $this->$method($collection, $query);
			}
		}
		$collection = array_values($collection);
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
	
	protected function filterSort(Array $collection, Query $query) {
		$orders = $query->getParam('order');
		if (empty($orders)) {
			return $collection;
		}
		ArrayHelper::multisort($collection, array_keys($orders), array_values($orders));
		return $collection;
	}
	
}
