<?php

namespace common\ddd\traits;

use common\ddd\BaseEntity;
use common\ddd\data\Query;
use yii\web\NotFoundHttpException;

trait ArrayModifyTrait {

	abstract public function oneById($id, Query $query = null);
	abstract protected function getCollection();
	abstract protected function setCollection(Array $collection);

	public function insert(BaseEntity $entity) {
		$collection = $this->getCollection();
		$collection[] = $entity->toArray();
		$this->setCollection($collection);
	}

	public function update(BaseEntity $entity) {
		$entityBase = $this->oneById($entity->{$this->primaryKey});
		$index = $this->getIndexOfEntity($entityBase);
		$collection = $this->getCollection();
		$collection[$index] = $entity->toArray();
		$this->setCollection($collection);
	}

	public function delete(BaseEntity $entity) {
		$index = $this->getIndexOfEntity($entity);
		$collection = $this->getCollection();
		unset($collection[$index]);
		$this->setCollection($collection);
	}

	protected function getIndexOfEntity(BaseEntity $entity) {
		$collection = $this->getCollection();
		foreach($collection as $index => $data) {
			if($data[$this->primaryKey] == $entity->{$this->primaryKey}) {
				return $index;
			}
		}
		throw new NotFoundHttpException;
	}

}