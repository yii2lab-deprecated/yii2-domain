<?php

namespace yii2lab\domain\values;

use yii\base\InvalidArgumentException;
use yii2lab\domain\interfaces\ValueObjectInterface;

class BaseValue implements ValueObjectInterface {
	
	private $value;
	
	public function __construct($value = null) {
		if(func_num_args() > 0) {
			$this->set($value);
		}
		$this->init();
	}
	
	public function init() {
	
	}
	
	public function set($value) {
		$this->value = $this->encode($value);
	}
	
	public function get($default = null) {
		$value = null;
		if(self::has()) {
			$value = $this->value;
		} else {
			if(func_num_args() > 0) {
				$value = $default;
			} else {
				$value = $this->getDefault();
			}
		}
		return $this->decode($value);
	}
	
	public function has() {
		return $this->value !== null;
	}
	
	final public function encode($value) {
		if(!$this->isValid($value)) {
			$this->showInvalidException();
		}
		return $this->_encode($value);
	}
	
	final public function decode($value) {
		return $this->_decode($value);
	}
	
	public function getDefault() {
		return null;
	}
	
	public function isValid($value) {
		return true;
	}
	
	protected function _encode($value) {
		return $value;
	}
	
	protected function _decode($value) {
		return $value;
	}
	
	protected function showInvalidException() {
		throw new InvalidArgumentException('Invalid value in "ValueObject"');
	}
	
}
