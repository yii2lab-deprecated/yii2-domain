<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\interfaces\repositories\ModifyInterface;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii\web\NotFoundHttpException;

class ActiveCoreRepository extends CoreRepository implements ReadInterface, ModifyInterface {
	
	public function all(Query $query = null) {
		$query = $this->forgeQuery($query);
		$params = $query->rest->getParams();
		$response = $this->get(null, $params);
		return $this->forgeEntity($response->data);
	}
	
	public function count(Query $query = null) {
		$query = $this->forgeQuery($query);
		$params = $query->rest->getParams();
		$response = $this->get(null, $params);
		return $response->headers->get('x-pagination-total-count');
	}

	public function one(Query $query = null) {
		/** @var Query $query */
		$query = $this->forgeQuery($query);
		$collection = $this->all($query);
		if(empty($collection)) {
			throw new NotFoundHttpException(static::class);
		}
		return $collection[0];
	}

	public function oneById($id, Query $query = null) {
		$response = $this->get($id);
		return $this->forgeEntity($response->data);
	}
	
	public function insert(BaseEntity $entity) {
		$this->post(null, $entity->toArray());
	}
	
	public function update(BaseEntity $entity) {
		$id = $this->getIdFromEntity($entity);
		$this->put($id, $entity->toArray());
	}
	
	public function delete(BaseEntity $entity) {
		$id = $this->getIdFromEntity($entity);
		$this->del($id);
	}
	
	protected function getIdFromEntity(BaseEntity $entity) {
		$id = $entity->{$this->primaryKey};
		return $id;
	}
	
}