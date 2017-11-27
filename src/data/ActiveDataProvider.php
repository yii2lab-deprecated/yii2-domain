<?php

namespace yii2lab\domain\data;

use yii\data\BaseDataProvider;
use yii\db\ActiveQueryInterface;

class ActiveDataProvider extends BaseDataProvider {

	public $query;
	public $service;
	public $key;
	
	/**
	 * @inheritdoc
	 */
	protected function prepareModels() {
		$query = $this->cloneQueryClass();
		$pagination = $this->getPagination([]);
		if($pagination !== false) {
			$pagination->totalCount = $this->getTotalCount();
			if($pagination->totalCount === 0) {
				return [];
			}
		}
		$offset = $query->getParam('offset', 'integer');
		if($offset == 0) {
			$offset = $pagination->getOffset();
		} else {
			$offset = $offset < $pagination->totalCount ? $offset : $pagination->totalCount;
		}
		$query->limit($pagination->getLimit())->offset($offset);
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
		$query = $this->cloneQueryClass();
		$query->limit(null)->offset(null)->orderBy(null);
		return (int) $this->service->count($query);
	}
	
	/**
	 * @return Query
	 */
	private function cloneQueryClass() {
		$query = $this->getQueryClass();
		return clone $query;
	}
	
	/**
	 * @return Query
	 */
	private function getQueryClass() {
		if (!$this->query instanceof Query) {
			$this->query = new Query;
		}
		return $this->query;
	}
}
