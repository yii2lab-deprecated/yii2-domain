<?php

namespace common\ddd\repositories;

use common\ddd\data\Query;
use common\ddd\helpers\QueryValidator;
use common\ddd\interfaces\repositories\BaseInterface;
use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\store\Store;

class DiscRepository extends BaseRepository {
	
	private $rowList;
	protected $table;
	protected $format = 'php';
	protected $db = 'common/data';
	
	public function init() {
		parent::init();
		$this->loadTableData();
	}
	
	public function allFields() {
		if(empty($this->rowList)) {
			return [];
		}
		$attributes = array_keys($this->rowList[0]);
		return $this->alias->decode($attributes);
	}
	
	protected function oneModel(Query $query = null) {
		$query = $this->forgeQuery($query);
		$this->queryValidator->validateSelectFields($query);
		$this->queryValidator->validateWhereFields($query);
		$condition = $this->forgeCondition([], $query);
		$model = ArrayHelper::findOne($this->rowList, $condition);
		if(empty($model)) {
			throw new NotFoundHttpException();
		}
		return $model;
	}
	
	protected function allModels(Query $query = null) {
		$query = $this->forgeQuery($query);
		$this->queryValidator->validateSelectFields($query);
		$this->queryValidator->validateWhereFields($query);
		$this->queryValidator->validateSortFields($query);
		$condition = $this->forgeCondition([], $query);
		if(!empty($condition)) {
			$models = ArrayHelper::findAll($this->rowList, $condition);
		} else {
			$models = $this->rowList;
		}
		return $models;
	}
	
	protected function forgeCondition($condition = [], Query $query) {
		$where = $query->getParam('where');
		if(!empty($where)) {
			foreach($where as $key => $value) {
				$encodedName = $this->alias->encode($key);
				$condition[ $encodedName ] = $value;
			}
		}
		return $condition;
	}
	
	protected function findOne($condition) {
		$user = ArrayHelper::findOne($this->rowList, $condition);
		return $this->forgeEntity($user);
	}
	
	protected function findAll($condition) {
		$user = ArrayHelper::findAll($this->rowList, $condition);
		return $this->forgeEntity($user);
	}
	
	private function loadTableData() {
		if(isset($this->rowList) || empty($this->table)) {
			return;
		}
		$dir = Yii::getAlias('@' . $this->db);
		$fileName = $dir . DS . $this->table . DOT . $this->format;
		$store = new Store($this->format);
		$this->rowList = $store->load($fileName);
		if(empty($this->rowList)) {
			$this->rowList = [];
		}
	}
	
}