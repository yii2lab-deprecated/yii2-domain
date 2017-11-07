<?php

namespace yii2lab\domain\data\query;

use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii2lab\domain\data\Query;

class Rest extends Component {
	
	/** @var Query */
	public $query;
	
	public function getParams() {
		$params = [];
		if(empty($this->query)) {
			return [];
		}
		
		$fields = $this->getFields();
		$params = ArrayHelper::merge($params, $fields);
		
		$with = $this->getWith();
		$params = ArrayHelper::merge($params, $with);
		
		$sort = $this->getSort();
		$params = ArrayHelper::merge($params, $sort);
		
		$pagination = $this->getPagination();
		$params = ArrayHelper::merge($params, $pagination);
		
		$where = $this->getWhere();
		$params = ArrayHelper::merge($params, $where);
		
		return $params;
	}
	
	private function getFields() {
		$params = [];
		$select = $this->query->getParam('select');
		if($select) {
			$params['fields'] = implode(',', $select);
		}
		return $params;
	}
	
	private function getWith() {
		$params = [];
		$with = $this->query->getParam('with');
		if($with) {
			$params['expand'] = implode(',', $with);
		}
		return $params;
	}
	
	private function getSort() {
		$params = [];
		$order = $this->query->getParam('order');
		if($order) {
			$sort = [];
			foreach($order as $name => $direction) {
				$prefix = $direction == SORT_DESC ? '-' : '';
				$sort[] = $prefix . $name;
			}
			$params['sort'] = implode(',', $sort);
		}
		return $params;
	}
	
	private function getWhere() {
		$params = [];
		$where = $this->query->getParam('where');
		if($where) {
			foreach($where as $name => $value) {
				if(!isset($params[$name])) {
					if(is_bool($value)) {
						$value = intval($value);
					}
					$params[$name] = $value;
				}
			}
		}
		return $params;
	}
	
	private function getPagination() {
		$params = [];
		$offset = $this->query->getParam('offset', 'integer');
		if($offset) {
			$params['offset'] = $offset;
		}
		$limit = $this->query->getParam('limit', 'integer');
		if($limit) {
			$params['limit'] = $limit;
		}
		$page = $this->query->getParam('page', 'integer');
		if($page) {
			$params['page'] = $page;
		}
		$prePage = $this->query->getParam('per-page', 'integer');
		if($prePage) {
			$params['per-page'] = $prePage;
		}
		return $params;
	}
	
}
