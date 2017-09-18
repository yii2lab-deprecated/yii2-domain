<?php

namespace common\ddd\data;

use yii\data\BaseDataProvider;
use yii\db\ActiveQueryInterface;
use yii\base\InvalidConfigException;

class ActiveDataProvider extends BaseDataProvider {

	public $query;
	public $service;
	public $key;
	
	public function init() {
	
	}
	
	/**
	 * @inheritdoc
	 */
	protected function prepareModels() {
		$this->checkQueryClass();
		$query = clone $this->query;
		if(($pagination = $this->getPagination()) !== false) {
			$pagination->totalCount = $this->getTotalCount();
			if($pagination->totalCount === 0) {
				return [];
			}
			$query->limit($pagination->getLimit())->offset($pagination->getOffset());
		}
		return $this->service->all($query);
	}
	
	/**
	 * @inheritdoc
	 */
	protected function prepareKeys($models) {
		$keys = [];
		if($this->key !== null) {
			foreach($models as $model) {
				if(is_string($this->key)) {
					$keys[] = $model[ $this->key ];
				} else {
					$keys[] = call_user_func($this->key, $model);
				}
			}
			
			return $keys;
		} elseif($this->query instanceof ActiveQueryInterface) {
			/* @var $class \yii\db\ActiveRecordInterface */
			$class = $this->query->modelClass;
			$pks = $class::primaryKey();
			if(count($pks) === 1) {
				$pk = $pks[0];
				foreach($models as $model) {
					$keys[] = $model[ $pk ];
				}
			} else {
				foreach($models as $model) {
					$kk = [];
					foreach($pks as $pk) {
						$kk[ $pk ] = $model[ $pk ];
					}
					$keys[] = $kk;
				}
			}
			
			return $keys;
		} else {
			return array_keys($models);
		}
	}
	
	/**
	 * @inheritdoc
	 */
	protected function prepareTotalCount() {
		$this->checkQueryClass();
		$query = clone $this->query;
		$query->limit(null)->offset(null)->orderBy(null);
		return (int) $this->service->count($query);
	}
	
	protected function checkQueryClass() {
		if (!$this->query instanceof Query) {
			throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. Query or its subclasses.');
		}
	}
}
