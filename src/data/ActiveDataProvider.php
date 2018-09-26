<?php

namespace yii2lab\domain\data;

use yii\data\BaseDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQueryInterface;
use yii2lab\domain\interfaces\services\CrudInterface;

class ActiveDataProvider extends BaseDataProvider {
	
	/**
	 * @var Query
	 */
	public $query;
	/**
	 * @var CrudInterface
	 */
	public $service;
	public $key;
	
	/**
	 * @inheritdoc
	 */
	protected function prepareModels() {
		$pagination = $this->getPagination();
		if($pagination !== false) {
			$pagination->totalCount = $this->getTotalCount();
			if($pagination->totalCount === 0) {
				return [];
			}
		}
		$query = $this->prepareQueryFromPagination($pagination);
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
	protected function prepareTotalCount() : int {
		$query = $this->cloneQueryClass();
		$query->limit(null)->offset(null)->orderBy(null);
		return (int) $this->service->count($query);
	}
	
	/**
	 * @return Query
	 */
	private function cloneQueryClass() : Query {
		$query = $this->getQueryClass();
		return clone $query;
	}
	
	/**
	 * @return Query
	 */
	private function getQueryClass() : Query {
		if (!$this->query instanceof Query) {
			$this->query = new Query;
		}
		return $this->query;
	}
	
	private function prepareQueryFromPagination(Pagination $pagination) : Query {
		$query = $this->cloneQueryClass();
		$offset = $query->getParam('offset');
		$page = $query->getParam('page');
		$perPage = $query->getParam('per-page', 'integer');
		if($perPage) {
			$pagination->setPageSize($perPage, true);
		}
		/*if($page !== null) {
			$offset = $pagination->getLimit() * ($page + 1);
		} elseif($offset !== null) {
			$offset = $this->normalizeOffset($offset, $pagination);
		}*/
		$offset = $this->normalizeOffset($offset, $pagination);
		
		$query->limit($pagination->getLimit());
		$query->offset($offset);
		return $query;
	}
	
	private function normalizeOffset($offset, Pagination $pagination) : int {
		if($offset === null) {
			$offset = $pagination->getOffset();
		} else {
			$offset = $offset < $pagination->totalCount ? $offset : $pagination->totalCount;
		}
		return intval($offset);
	}
	
}
