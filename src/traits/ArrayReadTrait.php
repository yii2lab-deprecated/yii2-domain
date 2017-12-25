<?php

namespace yii2lab\domain\traits;

use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use yii\web\NotFoundHttpException;
use yii2lab\domain\helpers\RelationHelper;

/**
 * @property string $primaryKey
 */

trait ArrayReadTrait {

	abstract public function forgeEntity($data, $class = null);
	abstract protected function getCollection();
	abstract public function relations();
	
	/**
	 * @param $query
	 *
	 * @return Query
	 */
	abstract protected function forgeQuery($query = null);

	public function isExists($query) {
		/** @var Query $query */
		if(is_array($query)) {
			$q = Query::forge();
			$q->whereFromCondition($query);
			$query = $q;
		}
		$query = Query::forge($query);
		try {
			$this->one($query);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}

	public function isExistsById($id) {
		try {
			$this->oneById($id);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function oneById($id, Query $query = null) {
		/** @var Query $query */
		$query = Query::forge($query);
		$query->where($this->primaryKey, $id);
		return $this->one($query);
	}
	
	public function one(Query $query = null) {
		/** @var Query $query */
		$query = Query::forge($query);
		$with = RelationHelper::cleanWith($this->relations(), $query);
		$collection = $this->all($query);
		if(empty($collection)) {
			throw new NotFoundHttpException(static::class);
		}
		$entity = $collection[0];
		if(!empty($with)) {
			$relations = $this->relations();
			$entity = RelationHelper::one($relations, $with, $entity);
		}
		return $entity;
	}

	public function all(Query $query = null) {
		$query = Query::forge($query);
		$with = RelationHelper::cleanWith($this->relations(), $query);
		$iterator = $this->getIterator();
		$array = $iterator->all($query);
		$collection = $this->forgeEntity($array);
		if(!empty($with)) {
			$relations = $this->relations();
			$collection = RelationHelper::all($relations, $with, $collection);
		}
		return $collection;
	}
	
	public function count(Query $query = null) {
		$query = Query::forge($query);
		$iterator = $this->getIterator();
		return $iterator->count($query);
	}

	private function getIterator() {
		$collection = $this->getCollection();
		$iterator = new ArrayIterator();
		$iterator->setCollection($collection);
		return $iterator;
	}
	
}