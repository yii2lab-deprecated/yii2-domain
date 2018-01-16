<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii2lab\domain\data\Query;
use yii\base\Object;
use yii\web\BadRequestHttpException;

class QueryValidator extends Object {
	
	/** @var \yii2lab\domain\repositories\BaseRepository */
	public $repository;
	
	public function validateSortFields(Query $query) {
		$this->filterFields($query->getParam('order'), 'sortFields');
	}
	
	public function validateWhereFields(Query $query) {
		// todo: сделать более совершенную валидацию условия, учитывая OR и AND
		//$this->filterFields($query->getParam('where'), 'whereFields');
	}
	
	public function validateSelectFields(Query $query) {
		if(empty($query)) {
			return;
		}
		/** @var Query $query */
		$fields = $query->getParam('select');
		if(empty($fields)) {
			return;
		}
		$entityAttributes = $this->repository->selectFields();
		$diff = array_diff($fields, $entityAttributes);
		if(!empty($diff)) {
			$fieldName = $diff[ key($diff) ];
			$message = Yii::t('db', 'field_not_exists {field}', ['field' => $fieldName]);
			throw new BadRequestHttpException($message);
		}
	}
	
	private function filterFields($data, $type) {
		if(empty($data) || !is_array($data)) {
			return [];
		}
		$fields = $this->repository->$type();
		if(empty($fields)) {
			throw new BadRequestHttpException(t('exception', 'not_allowed_to_use_parameter_in_' . $type));
		}
		foreach($data as $name => $value) {
			if(!in_array($name, $fields)) {
				throw new BadRequestHttpException(t('exception', 'not_allowed_to_use_parameter_in_' . $type . ' {parameter}', ['parameter' => $name]));
			}
		}
		return $data;
	}
	
}