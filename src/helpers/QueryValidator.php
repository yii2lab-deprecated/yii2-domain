<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\db\Expression;
use yii2lab\domain\data\Query;
use yii\base\BaseObject;
use yii\web\BadRequestHttpException;
use yii2lab\domain\exceptions\BadQueryHttpException;

class QueryValidator extends BaseObject {
	
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
			$message = Yii::t('domain/db', 'field_not_exists {field}', ['field' => $fieldName]);
			throw new BadQueryHttpException($message);
		}
	}
	
	private function filterFields($data, $type) {
		if(empty($data) || !is_array($data)) {
			return [];
		}
		$fields = $this->repository->$type();
		if(empty($fields)) {
			throw new BadQueryHttpException(Yii::t('domain/exception', 'not_allowed_to_use_parameter_in_' . $type));
		}
		foreach($data as $name => $value) {
			if($value instanceof Expression) {
			
			} else {
				if(!in_array($name, $fields)) {
					throw new BadQueryHttpException(Yii::t('domain/exception', 'not_allowed_to_use_parameter_in_' . $type . ' {parameter}', ['parameter' => $name]));
				}
			}
		}
		return $data;
	}
	
}