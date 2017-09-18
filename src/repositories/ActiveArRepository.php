<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii2lab\domain\interfaces\repositories\ModifyInterface;
use yii2lab\domain\traits\ActiveRepositoryTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

class ActiveArRepository extends ArRepository implements ReadInterface, ModifyInterface {
	
	use ActiveRepositoryTrait;
	
	public function count(Query $query = null) {
		$this->queryValidator->validateWhereFields($query);
		$this->resetQuery();
		$this->forgeQueryForAll($query);
		$this->forgeQueryForWhere($query);
		return (int) $this->query->count();
	}
	
	protected function forgeUniqueFields() {
		$unique = $this->uniqueFields();
		if(!empty($unique)) {
			$unique = ArrayHelper::toArray($unique);
		}
		if(!empty($this->primaryKey)) {
			$unique[] = [$this->primaryKey];
		}
		return $unique;
	}
	
	protected function findUnique(BaseEntity $entity, $isUpdate = false) {
		$unique = $this->forgeUniqueFields();
		foreach($unique as $uniqueItem) {
			$this->findUniqueItem($entity, $uniqueItem, $isUpdate);
		}
	}
	
	public function insert(BaseEntity $entity) {
		$this->findUnique($entity);
		/** @var ActiveRecord $model */
		$model = Yii::createObject($this->model->className());
		$this->massAssignment($model, $entity, self::SCENARIO_INSERT);
		$this->saveModel($model);
	}
	
	// todo: rename item to model
	
	public function update(BaseEntity $entity) {
		$this->findUnique($entity, true);
		$entity->validate();
		$entityPk = $entity->{$this->primaryKey};
		$model = $this->findOne([$this->primaryKey => $entityPk]);
		$this->massAssignment($model, $entity, self::SCENARIO_UPDATE);
		$this->saveModel($model);
	}
	
	public function delete(BaseEntity $entity) {
		$entityPk = $entity->{$this->primaryKey};
		$condition = [$this->primaryKey => $entityPk];
		$model = $this->findOne($condition);
		$model->delete();
	}
	
	/**
	 * @param $condition
	 *
	 * @return ActiveRecord
	 * @throws NotFoundHttpException
	 */
	protected function findOne($condition) {
		$condition = $this->alias->encode($condition);
		$model = $this->model->findOne($condition);
		if(empty($model)) {
			throw new NotFoundHttpException();
		}
		return $model;
	}
	
}