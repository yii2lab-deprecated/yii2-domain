<?php

namespace yii2lab\domain\data;

use yii\web\NotFoundHttpException;
use yii2lab\designPattern\scenario\helpers\ScenarioHelper;
use yii2lab\helpers\ReflectionHelper;
use yii2lab\designPattern\filter\helpers\FilterHelper;

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
			throw new NotFoundHttpException(__METHOD__ . ':' . __LINE__);
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
				$filterConfig = [
					'class' => 'yii2lab\domain\filters\iterator\\' . ucfirst($filterName),
					'query' => $query,
				];
				$collection = ScenarioHelper::run($filterConfig, $collection);
			}
		}
		$collection = array_values($collection);
		return $collection;
	}
	
}
