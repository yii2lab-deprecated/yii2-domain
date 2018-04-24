<?php

namespace yii2lab\domain\traits;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;
use yii2lab\domain\helpers\repository\RelationHelper;
use yii2lab\domain\helpers\repository\RelationWithHelper;
use yii\base\InvalidArgumentException;
use yii2mod\helpers\ArrayHelper;

trait ActiveRepositoryTrait {
	
	/**
	 * @param Query|null $query
	 *
	 * @return Query
	 */
	abstract protected function prepareQuery(Query $query = null);
	
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
		$query = $this->prepareQuery();
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
	
	/**
	 * @param            $id
	 * @param Query|null $query
	 *
	 * @return BaseEntity
	 *
	 * @throws NotFoundHttpException
	 */
	public function oneById($id, Query $query = null) {
		/** @var Query $query */
		$query = $this->prepareQuery($query);
		$query->removeParam('where');
		$query->where($this->primaryKey, $id);
		return $this->one($query);
	}
	
	/**
	 * @param Query|null $query
	 *
	 * @return BaseEntity
	 *
	 * @throws NotFoundHttpException
	 * @throws \yii\web\BadRequestHttpException
	 */
	public function one(Query $query = null) {
		$query = $this->prepareQuery($query);
		if(!$query->hasParam('where') || $query->getParam('where') == []) {
			throw new InvalidArgumentException(Yii::t('domain:domain/repository', 'where_connot_be_empty'));
		};
		$query->limit(1);
		$collection = $this->all($query);
		if(empty($collection)) {
			throw new NotFoundHttpException();
		}
		$entity = ArrayHelper::first($collection);
		return $entity;
	}
	/*public function one(Query $query = null) {
		$query = $this->prepareQuery($query);
		if(!$query->hasParam('where') || $query->getParam('where') == []) {
		    throw new InvalidArgumentException(\Yii::t('domain:domain/repository', 'where_connot_be_empty'));
		};
		$query2 = clone $query;
		$with = RelationWithHelper::cleanWith($this->relations(), $query);
		$model = $this->oneModel($query);
		if(empty($model)) {
			throw new NotFoundHttpException();
		}
		$entity = $this->forgeEntity($model);
		if(!empty($with)) {
			$entity = RelationHelper::load($this->domain->id, $this->id, $query2, $entity);
		}
		return $entity;
	}*/
	
	public function all(Query $query = null) {
		$query = $this->prepareQuery($query);
		$query2 = clone $query;
		$with = RelationWithHelper::cleanWith($this->relations(), $query);
		$models = $this->allModels($query);
		$collection = $this->forgeEntity($models);
		if(!empty($with)) {
			$collection = RelationHelper::load($this->domain->id, $this->id, $query2, $collection);
		}
		return $collection;
	}
	
	protected function oneModelByCondition($condition, Query $query = null) {
		/** @var Query $query */
		$query = $this->prepareQuery($query);
		$query->whereFromCondition($condition);
		$model = $this->oneModel($query);
		if(empty($model)) {
			throw new NotFoundHttpException();
		}
		return $model;
	}
	
	protected function allModelsByCondition($condition = [], Query $query = null) {
		/** @var Query $query */
		$query = $this->prepareQuery($query);
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
					$error->add($name, 'domain/db', 'already_exists {value}', ['value' => $entity->{$name}]);
				}
				throw new UnprocessableEntityHttpException($error);
			}

		} catch(NotFoundHttpException $e) {
			
		}
	}
	
}