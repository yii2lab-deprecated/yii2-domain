<?php

namespace yii2lab\domain\repositories;

use yii\web\ServerErrorHttpException;
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
		$entity->validate();
		$this->findUnique($entity);
		/** @var ActiveRecord $model */
		$model = Yii::createObject(get_class($this->model));
		$this->massAssignment($model, $entity, self::SCENARIO_INSERT);
		$result = $this->saveModel($model);
		if(!empty($this->primaryKey) && $result) {
			try {
				//TODO: а как же блокировка транзакции? Выяснить!
				$sequenceName = empty($this->tableSchema['sequenceName']) ? '' : $this->tableSchema['sequenceName'];
				$id = Yii::$app->db->getLastInsertID($sequenceName);
				$entity->{$this->primaryKey} = $id;
				
				// todo: как вариант
				/*$tableSchema = Yii::$app->db->getTableSchema($this->tableSchema['name']);
				$entity->{$this->primaryKey} =  Yii::$app->db->getLastInsertID($tableSchema->sequenceName);*/
				
			}catch(\Exception $e) {
				throw new ServerErrorHttpException('Postgre sequence error');
			}
		}
		return $entity;
	}
	
	public function update(BaseEntity $entity) {
		$entity->validate();
		$this->findUnique($entity, true);
		$entityPk = $entity->{$this->primaryKey};
		$model = $this->findOne([$this->primaryKey => $entityPk]);
		$this->massAssignment($model, $entity, self::SCENARIO_UPDATE);
		$this->saveModel($model);
	}
	
	public function delete(BaseEntity $entity) {
		if(!empty($this->primaryKey)) {
			$entityPk = $entity->{$this->primaryKey};
			$condition = [$this->primaryKey => $entityPk];
		} else {
			$condition = $entity->toArray();
		}
		$model = $this->findOne($condition);
		$this->model::deleteAll($condition);
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
			throw new NotFoundHttpException(static::class);
		}
		return $model;
	}
	
}
