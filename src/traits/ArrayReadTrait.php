<?php

namespace yii2lab\domain\traits;

use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * @property string $primaryKey
 */

trait ArrayReadTrait {

	abstract public function forgeEntity($data, $class = null);
	abstract protected function getCollection();
	abstract protected function forgeQuery($query);

	public function isExists($query) {
		/** @var Query $query */
		if(is_array($query)) {
			$query = $query->whereFromCondition($query);
		}
		$query = $this->forgeQuery($query);
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
		$query = $this->forgeQuery($query);
		$query->where($this->primaryKey, $id);
		return $this->one($query);
	}
	
	public function one(Query $query = null) {
		/** @var Query $query */
		$query = $this->forgeQuery($query);
		$collection = $this->all($query);
		if(empty($collection)) {
			throw new NotFoundHttpException;
		}
		$entity = $this->forgeEntity($collection[0]);
		return $entity;
	}

	public function all(Query $query = null) {
		$query = $this->forgeQuery($query);
		$iterator = $this->getIterator();
		$collection = $iterator->all($query);
		return $this->forgeEntity($collection);
	}
	
	public function count(Query $query = null) {
		$query = $this->forgeQuery($query);
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