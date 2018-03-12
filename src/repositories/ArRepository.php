<?php

namespace yii2lab\domain\repositories;

use yii\base\Model;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use Yii;
use yii\base\UnknownMethodException;
use yii\db\IntegrityException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii2mod\helpers\ArrayHelper;

class ArRepository extends BaseRepository {
	
	public $tableName = null;
	
	/** @var  \yii\db\ActiveRecord */
	protected $modelClass;
	
	/** @var  \yii\db\ActiveRecord */
	protected $model;
	
	/** @var  \yii\db\ActiveQuery */
	protected $query;
	protected $tableSchema;
	
	public function init() {
		parent::init();
		$this->initModel();
		$this->initQuery();
	}
	
	public function tableName()
	{
		return $this->tableName;
	}
	
	public function getModel() {
		return $this->model;
	}
	
	protected function resetQuery() {
		$this->initQuery();
	}
	
	public function autoIncrementField() {
		if(empty($this->tableSchema['columns'])) {
			return null;
		}
		foreach($this->tableSchema['columns'] as $name => $data) {
			if($data['autoIncrement']) {
				return $name;
			}
		}
		return null;
	}
	
	public function allFields() {
		$attributes = $this->model->attributes();
		return $this->alias->decode($attributes);
	}
	
	protected function isYiiModel($model) {
		return $model instanceof yii\db\ActiveRecord;
	}
	
	private function createVirtualModel() {
		if(isset($this->model)) {
			return;
		}
		$model = $this->domain->factory->model->createVirtual($this->tableName(), $this->getParentModelClassName(), ['primaryKey'=>$this->primaryKey]);
		$this->modelClass = $model['baseName'];
		$this->model = $model['model'];
	}
	
	private function getParentModelClassName() {
		if($this instanceof FiledbRepository || $this instanceof ActiveFiledbRepository) {
			return 'yii2tech\filedb\ActiveRecord';
		} else {
			return 'yii\db\ActiveRecord';
		}
	}
	
	private function initModel() {
		if($this->tableName()) {
			$this->createVirtualModel();
		} else {
			if(!isset($this->modelClass)) {
				$this->modelClass = $this->domain->factory->model->genClassName($this->id);
			}
			$this->model = $this->domain->factory->model->create($this->modelClass);
		}
		
		if($this->primaryKey !== false) {
			$primaryKey = $this->model->primaryKey();
		}
		if(!empty($primaryKey)) {
			$this->primaryKey = $this->alias->decode($primaryKey[0]);
		}
		if(method_exists($this->model, 'getTableSchema')) {
			$this->tableSchema = ArrayHelper::toArray($this->model->getTableSchema());
		}
	}
	
	protected function initQuery() {
		$this->query = $this->model->find();
	}
	
	protected function oneModel(Query $query = null) {
		$this->resetQuery();
		$query = Query::forge($query);
		$this->getQueryValidator()->validateSelectFields($query);
		$this->getQueryValidator()->validateWhereFields($query);
		$this->forgeQueryForOne($query);
		$this->forgeQueryForWhere($query);
		$model = $this->query->one();
		if(empty($model)) {
			throw new NotFoundHttpException(static::class);
		}
		$modelData = $this->modelToArray($model, $query);
		return $modelData;
	}
	
	protected function allModels(Query $query = null) {
		$this->resetQuery();
		$query = Query::forge($query);
		$this->getQueryValidator()->validateSelectFields($query);
		$this->getQueryValidator()->validateWhereFields($query);
		$this->getQueryValidator()->validateSortFields($query);
		$this->forgeQueryForAll($query);
		$this->forgeQueryForWhere($query);
		$models = $this->query->all();
		$modelData = $this->modelToArray($models, $query);
		return $modelData;
	}
	
	protected function saveModel(Model $model) {
		return $model->save();
		try {
		
		} catch(IntegrityException $e) {
			$error = new ErrorCollection();
			if($e->getCode() == 23503 || $e->getCode() == 23000) {
				$error->add(null, 'domain/db', 'integrity_constraint_violation');
				throw new UnprocessableEntityHttpException($error);
			} elseif($e->getCode() == 23505 /*|| $e->getCode() == 23000*/) {
				$error->add(null, 'domain/db', 'already_exists');
				throw new UnprocessableEntityHttpException($error);
			} else {
				throw new BadRequestHttpException;
			}
		}
		return false;
	}
	
	// todo: deprecated
	protected function unsetNotExistedFields(Model $model, $data) {
		$modelAttributes = array_keys($model->attributes);
		foreach($data as $name => $value) {
			if(!in_array($name, $modelAttributes)) {
				unset($data[ $name ]);
			}
		}
		return $data;
	}
	
	protected function unsetFieldsByKey($keys, $data) {
		if(empty($keys)) {
			return $data;
		}
		foreach($data as $name => $value) {
			if(!in_array($name, $keys)) {
				unset($data[ $name ]);
			}
		}
		return $data;
	}
	
	protected function massAssignment(Model $model, BaseEntity $entity, $scenario = null) {
		$data = $entity->toArray();
		$data = $this->unsetFieldsByKey($this->allFields(), $data);
		$scenarios = $this->scenarios();
		if(!empty($scenarios[ $scenario ]) && !empty($scenario)) {
			$data = $this->unsetFieldsByKey($scenarios[ $scenario ], $data);
		}
		$dataAlias = $this->alias->encode($data);
		Yii::configure($model, $dataAlias);
	}
	
	protected function getModelExtraFields() {
		try {
			$extraFields = $this->model->extraFields();
		} catch(UnknownMethodException $e) {
			$extraFields = [];
		}
		return $extraFields;
	}
	
	protected function forgeQueryForOne(Query $query) {
		if(empty($query)) {
			return;
		}
		$q = $query->toArray();
		if(!empty($q['select'])) {
			$fields = $this->alias->encode($q['select']);
			$this->query->select($fields);
		}
		if(!empty($q['with'])) {
			$this->validateWithParam($q['with']);
			$with = $this->alias->encode($q['with']);
			$this->query->with($with);
		}
	}
	
	protected function forgeQueryForWhere(Query $query) {
		if(empty($query)) {
			return;
		}
		$q = $query->toArray();
		if(!empty($q['where'])) {
			$where = $this->alias->encode($q['where']);
			$this->query->where($where);
		}
	}
	
	protected function forgeQueryForAll(Query $query) {
		if(empty($query)) {
			return;
		}
		$limit = $query->getParam('limit');
		if($limit) {
			$this->query->limit($limit);
		}
		$offset = $query->getParam('offset');
		if($offset) {
			$this->query->offset($offset);
		}
		$order = $query->getParam('order');
		if($order) {
			$orderEncoded = $this->alias->encode($order);
			$this->query->orderBy($orderEncoded);
		}
		$this->forgeQueryForOne($query);
	}
	
	protected function modelToArray($model, Query $query) {
		if(empty($model)) {
			return [];
		}
		if(ArrayHelper::isIndexed($model)) {
			$list = [];
			foreach($model as $item) {
				$list[] = $this->modelItemToArray($item, $query);
			}
			return $list;
		}
		return $this->modelItemToArray($model, $query);
	}
	
	private function validateWithParam($with) {
		/*$modelExtraFields = $this->getModelExtraFields();
		foreach($with as $key => $value) {
			if(!in_array($value, $modelExtraFields)) {
				throw new BadRequestHttpException(Yii::t('domain/exception', 'not_allowed_to_use_parameter_in_expand {parameter}', ['parameter' => $value]));
			}
		}*/
	}
	
	private function modelItemToArray(Model $model, Query $query) {
		$query = Query::forge($query);
		$withParam = $query->getParam('with');
		$expand = $withParam ? $withParam : [];
		$modelArray = $model->toArray([], $expand);
		return $modelArray;
	}
	
}