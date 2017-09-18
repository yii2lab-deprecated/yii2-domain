<?php

namespace common\ddd\data;

use common\ddd\helpers\TypeHelper;
use yii\base\Component;
use yii2mod\helpers\ArrayHelper;

class Query extends Component {
	
	private $query = [];

	public static function forge($query = null) {
		if($query instanceof Query) {
			return $query;
		}
		return new Query();
	}

	public function where($key, $value) {
		if($value === null) {
			unset($this->query['where'][ $key ]);
			//return $this;
		} else {
			$this->query['where'][ $key ] = $value;
		}
		return $this;
	}
	
	public function whereFromCondition($condition) {
		if(empty($condition)) {
			return;
		}
		if(!empty($condition)) {
			foreach($condition as $name => $value) {
				$this->where($name, $value);
			}
		}
	}
	
	/** todo: rename to fields */
	
	public function select($fields) {
		if($fields === null) {
			unset($this->query['select']);
			return $this;
		}
		$this->setParam($fields, 'select');
		return $this;
	}
	
	public function with($names) {
		$this->setParam($names, 'with');
		return $this;
	}
	
	public function page($value) {
		if($value === null) {
			unset($this->query['page']);
			return $this;
		}
		$this->query['page'] = $value;
		return $this;
	}
	
	public function perPage($value) {
		if($value === null) {
			unset($this->query['per-page']);
			return $this;
		}
		$this->query['per-page'] = $value;
		return $this;
	}
	
	public function limit($value) {
		if($value === null) {
			unset($this->query['limit']);
			return $this;
		}
		$this->query['limit'] = $value;
		return $this;
	}
	
	public function offset($value) {
		if($value === null) {
			unset($this->query['offset']);
			return $this;
		}
		$this->query['offset'] = $value;
		return $this;
	}
	
	public function orderBy($value) {
		if($value === null) {
			unset($this->query['order']);
			return $this;
		}
		$this->query['order'] = $value;
		return $this;
	}
	
	/** todo: rename to sort */
	
	public function addOrderBy($value) {
		$this->query['order'] = ArrayHelper::merge($this->query['order'], $value);
		return $this;
	}
	
	public function addOrder($field, $direction = SORT_ASC) {
		$this->query['order'][ $field ] = $direction;
		return $this;
	}
	
	/** todo: rename to toArray and create getParam method */
	
	public function data() {
		return $this->query;
	}
	
	public function hasParam($key) {
		return ArrayHelper::has($this->query, $key);
	}
	
	public function getParam($key, $type = null) {
		$value = ArrayHelper::getValue($this->query, $key);
		if(!empty($type)) {
			$value = TypeHelper::encode($value, $type);
		}
		return $value;
	}
	
	public function removeParam($key) {
		if(isset($this->query[ $key ])) {
			unset($this->query[ $key ]);
		}
	}
	
	public function getParamsForRest() {
		$params = [];
		if(empty($this->data())) {
			return [];
		}
		$select = $this->getParam('select');
		if($select) {
			$params['fields'] = implode(',', $select);
		}
		$with = $this->getParam('with');
		if($with) {
			$params['expand'] = implode(',', $with);
		}
		$order = $this->getParam('order');
		if($order) {
			$sort = [];
			foreach($order as $name => $direction) {
				$prefix = $direction == SORT_DESC ? '-' : '';
				$sort[] = $prefix . $name;
			}
			$params['sort'] = implode(',', $sort);
		}
		$offset = $this->getParam('offset', 'integer');
		if($offset) {
			$params['offset'] = $offset;
		}
		$limit = $this->getParam('limit', 'integer');
		if($limit) {
			$params['limit'] = $limit;
		}
		$page = $this->getParam('page', 'integer');
		if($page) {
			$params['page'] = $page;
		}
		$prePage = $this->getParam('per-page', 'integer');
		if($prePage) {
			$params['per-page'] = $prePage;
		}
		$where = $this->getParam('where');
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
	
	private function setParam($fields, $nameParam) {
		if(is_array($fields)) {
			if(isset($this->query[ $nameParam ])) {
				$this->query[ $nameParam ] = ArrayHelper::merge($this->query[ $nameParam ], $fields);
			} else {
				$this->query[ $nameParam ] = $fields;
			}
		} else {
			$this->query[ $nameParam ][] = $fields;
		}
		
	}
	
}
