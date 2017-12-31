<?php

namespace yii2lab\domain\traits;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;
use yii2lab\domain\helpers\Relation1Helper;
use yii2lab\domain\helpers\RelationHelper;

trait ActiveRepositoryTrait {
	
	public function relations() {
		return [];
	}
	
	public function isExistsById($id) {
		try {
			$this->oneById($id);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function isExists($condition) {
		/** @var Query $query */
		$query = Query::forge();
		if(is_array($condition)) {
			$query->whereFromCondition($condition);
		} else {
			$query->where($this->primaryKey, $condition);
		}
		try {
			$this->one($query);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function oneById($id, Query $query = null) {
		/** @var Query $query */
		$query = Query::forge($query);
		$query->removeParam('where');
		$query->where($this->primaryKey, $id);
		return $this->one($query);
	}
	
	public function one(Query $query = null) {
		$query = Query::forge($query);
		$with = RelationHelper::cleanWith($this->relations(), $query);
		$model = $this->oneModel($query);
		if(empty($model)) {
			throw new NotFoundHttpException();
		}
		$entity = $this->forgeEntity($model);
		if(!empty($with)) {
			$entity = Relation1Helper::load($this->domain->id, $this->id, $with, $entity);
		}
		return $entity;
	}
	
	public function all(Query $query = null) {
		$query = Query::forge($query);
		$with = RelationHelper::cleanWith($this->relations(), $query);
		$models = $this->allModels($query);
		$collection = $this->forgeEntity($models);
		if(!empty($with)) {
			$collection = Relation1Helper::load($this->domain->id, $this->id, $with, $collection);
		}
		return $collection;
	}
	
	protected function oneModelByCondition($condition, Query $query = null) {
		/** @var Query $query */
		$query = Query::forge($query);
		$query->whereFromCondition($condition);
		$model = $this->oneModel($query);
		if(empty($model)) {
			throw new NotFoundHttpException();
		}
		return $model;
	}
	
	protected function allModelsByCondition($condition = [], Query $query = null) {
		/** @var Query $query */
		$query = Query::forge($query);
		$query->whereFromCondition($condition);
		$models = $this->allModels($query);
		return $models;
	}
	
	protected function findUniqueItem(BaseEntity $entity, $uniqueItem, $isUpdate = false) {
		$condition = [];
		if(!empty($uniqueItem) && is_array($uniqueItem)) {
			foreach($uniqueItem as $name) {
				$entityValue = $entity->{$name};
				if(!empty($entityValue)) {
					$condition[ $name ] = $entityValue;
				}
			}
		}
		if(empty($condition)) {
			return;
		}
		try {
			$first = $this->oneModelByCondition($condition);
			$encodedPkName = $this->getAlias()->encode($this->primaryKey);
			if($isUpdate && $entity->{$this->primaryKey} == $first[$encodedPkName]) {

			} else {
				$error = new ErrorCollection();
				foreach($uniqueItem as $name) {
					$error->add($name, 'db', 'already_exists {value}', ['value' => $entity->{$name}]);
				}
				throw new UnprocessableEntityHttpException($error);
			}

		} catch(NotFoundHttpException $e) {
			
		}
	}
	
}