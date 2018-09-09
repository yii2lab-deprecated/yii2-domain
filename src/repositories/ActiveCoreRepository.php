<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\interfaces\repositories\CrudInterface;
use yii\web\NotFoundHttpException;

/**
 * Class ActiveCoreRepository
 *
 * @package yii2lab\domain\repositories
 * @deprecated use \yii2lab\extension\core\domain\repositories\base\BaseActiveCoreRepository
 */
abstract class ActiveCoreRepository extends CoreRepository implements CrudInterface {
	
	public function all(Query $query = null) {
		$query = Query::forge($query);
		$params = $query->rest->getParams();
		$response = $this->get(null, $params);
		return $this->forgeEntity($response->data);
	}
	
	public function count(Query $query = null) {
		$query = Query::forge($query);
		$params = $query->rest->getParams();
		$response = $this->get(null, $params);
		return $response->headers->get('x-pagination-total-count');
	}

	public function one(Query $query = null) {
		/** @var Query $query */
		$query = Query::forge($query);
		$collection = $this->all($query);
		if(empty($collection)) {
			throw new NotFoundHttpException(__METHOD__ . ':' . __LINE__);
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