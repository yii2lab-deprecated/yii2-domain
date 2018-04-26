<?php

namespace yii2lab\domain\traits;

use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use yii\web\NotFoundHttpException;
use yii2lab\domain\helpers\repository\RelationHelper;
use yii2lab\domain\helpers\repository\RelationWithHelper;

/**
 * @property string $primaryKey
 */

trait ArrayReadTrait {

	abstract public function forgeEntity($data, $class = null);
	abstract protected function getCollection();
	
	public function relations() {
		return [];
	}
	
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
		$query2 = clone $query;
		$with = RelationWithHelper::cleanWith($this->relations(), $query);
		$collection = $this->all($query);
		if(empty($collection)) {
			throw new NotFoundHttpException(__METHOD__ . ':' . __LINE__);
		}
		$entity = $collection[0];
		if(!empty($with)) {
			$entity = RelationHelper::load($this->domain->id, $this->id, $query2, $entity);
		}
		return $entity;
	}

	public function all(Query $query = null) {
		$query = Query::forge($query);
		$query2 = clone $query;
		$with = RelationWithHelper::cleanWith($this->relations(), $query);
		$iterator = $this->getIterator();
		$array = $iterator->all($query);
		$collection = $this->forgeEntity($array);
		if(!empty($with)) {
			$collection = RelationHelper::load($this->domain->id, $this->id, $query2, $collection);
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