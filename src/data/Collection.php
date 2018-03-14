<?php

namespace yii2lab\domain\data;

use yii2lab\helpers\yii\ArrayHelper;

class Collection extends BaseCollection {
	
	public function first() {
		if($this->count() == 0) {
			return null;
		}
		foreach($this as $value) {
			return $value;
		}
		return null;
	}
	
	public function last() {
		if($this->count() == 0) {
			return null;
		}
		return $this->items[ $this->count() - 1 ];
	}
	
	public function fetch() {
		if(!$this->valid()) {
			return false;
		}
		$item = $this->current();
		$this->next();
		return $item;
	}
	
	public function toArray() {
		return ArrayHelper::toArray($this->items);
	}
	
	public function load($array) {
		$this->loadItems($array);
	}
	
}
