<?php

namespace yii2lab\domain\data;

use ArrayAccess;
use Countable;
use Iterator;
use Serializable;

class BaseCollection implements ArrayAccess, Countable, Iterator, Serializable {
	
	protected $items = [];
	protected $position = 0;
	
	public function __construct(array $array = null) {
		if(!is_null($array)) {
			$this->items = $array;
		}
		$this->rewind();
	}
	
	public function offsetExists($offset) {
		return isset($this->items[ $offset ]);
	}
	
	public function offsetGet($offset) {
		return $this->offsetExists($offset) ? $this->items[ $offset ] : null;
	}
	
	public function offsetSet($offset, $value) {
		if(is_null($offset)) {
			$this->items[] = $value;
		} else {
			$this->items[ $offset ] = $value;
		}
	}
	
	public function offsetUnset($offset) {
		unset($this->items[ $offset ]);
	}
	
	public function rewind() {
		$this->position = 0;
	}
	
	public function current() {
		return $this->items[ $this->position ];
	}
	
	public function key() {
		return $this->position;
	}
	
	public function next() {
		++$this->position;
	}
	
	public function valid() {
		return isset($this->items[ $this->position ]);
	}
	
	public function count() {
		return count($this->items);
	}
	
	public function serialize() {
		return serialize($this->items);
	}
	
	public function unserialize($data) {
		$this->items = unserialize($data);
	}
	
	public function __invoke(array $data = null) {
		if(is_null($data)) {
			return $this->items;
		} else {
			$this->items = $data;
		}
	}
}
