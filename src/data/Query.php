<?php

namespace yii2lab\domain\data;

use Yii;
use yii2lab\domain\data\query\Rest;
use yii2lab\domain\helpers\TypeHelper;
use yii\base\Component;
use yii2mod\helpers\ArrayHelper;

/**
 * Class Query
 *
 * @package yii2lab\domain\data
 *
 * @property Rest $rest
 */
class Query extends Component {
	
	private $query = [];

	public static function forge($query = null) {
		if($query instanceof Query) {
			return $query;
		}
		return new Query();
	}

	public function where($key, $value) {
		if(func_num_args() == 1) {
			$this->query['where'] = $key;
		} else {
			$this->oldWhere($key, $value);
		}
		return $this;
	}
	
	public function andWhere($condition)
	{
		if ($this->query['where'] === null) {
			$this->query['where'] = $condition;
		} else {
			$this->query['where'] = ['and', $this->query['where'], $condition];
		}
		
		return $this;
	}
	
	public function orWhere($condition)
	{
		if ($this->query['where'] === null) {
			$this->query['where'] = $condition;
		} else {
			$this->query['where'] = ['or', $this->query['where'], $condition];
		}
		
		return $this;
	}
	
	private function oldWhere($key, $value) {
		if($value === null) {
			unset($this->query['where'][ $key ]);
		} else {
			$this->query['where'][ $key ] = $value;
		}
		return $this;
	}
	
	public function removeWhere($key) {
		unset($this->query['where'][ $key ]);
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
	
	public function toArray() {
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
		ArrayHelper::remove($this->query, $key);
	}
	
	public static function cloneForCount(Query $query = null) {
		$query = self::forge($query);
		$queryClone = self::forge();
		$queryClone->whereFromCondition($query->getParam('where'));
		return $queryClone;
	}
	
	public function getRest() {
		/** @var Rest $instance */
		$instance = Yii::createObject(Rest::className(), ['query' => $this]);
		return $instance;
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
