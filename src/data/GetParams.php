<?php

namespace common\ddd\data;

use yii\base\Component;
use yii\helpers\ArrayHelper;

class GetParams {
	
	public function convertParams($params = []) {
		$result = [];
		if(!empty($params['expand'])) {
			$result['expand'] = $this->splitStringParam($params['expand']);
			unset($params['expand']);
		}
		if(!empty($params['fields'])) {
			$result['fields'] = $this->splitStringParam($params['fields']);
			unset($params['fields']);
		}
		if(!empty($params['sort'])) {
			$params['sort'] = $this->splitStringParam($params['sort']);
			$result['sort'] = $this->splitSortParam($params['sort']);
			unset($params['sort']);
		}
		if(!empty($params['page'])) {
			$result['page'] = $params['page'];
			unset($params['page']);
		}
		if(!empty($params['per-page'])) {
			$result['per-page'] = $params['per-page'];
			unset($params['per-page']);
		}
		if(!empty($params)) {
			$result['where'] = $params;
		}
		return $result;
	}
	
	public function getAllParams($params = []) {
		$query = new Query();
		if(empty($params)) {
			return $query;
		}
		$params = $this->convertParams($params);
		
		if(!empty($params['expand'])) {
			$query->with($params['expand']);
		}
		if(!empty($params['fields'])) {
			$query->select($params['fields']);
		}
		if(!empty($params['sort'])) {
			foreach($params['sort'] as $name => $direction) {
				$query->addOrder($name, $direction);
			}
		}
		if(!empty($params['page'])) {
			$query->page($params['page']);
		}
		if(!empty($params['per-page'])) {
			$query->perPage($params['per-page']);
		}
		if(!empty($params['where'])) {
			foreach($params['where'] as $name => $value) {
				$query->where($name, $value);
			}
		}
		return $query;
	}
	
	protected function splitStringParam($value) {
		if(empty($value) || !is_string($value)) {
			return [];
		}
		$values = preg_split('/\s*,\s*/', $value, -1, PREG_SPLIT_NO_EMPTY);
		return $values;
	}
	
	protected function splitSortParam($params) {
		$sortParams = [];
		foreach($params as $sort) {
			if(strpos($sort, '-') !== false) {
				$name = substr($sort, 1);
				$direction = SORT_DESC;
			} else {
				$name = $sort;
				$direction = SORT_ASC;
			}
			$sortParams[ $name ] = $direction;
		}
		//$result['sort'] = $sortParams;
		return $sortParams;
	}
	
}
