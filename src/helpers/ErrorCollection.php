<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

class ErrorCollection {
	
	protected $error = [];
	
	public function __construct($field = null, $file = null, $name = null, $values = []) {
		if(func_num_args() >= 3) {
			$this->add($field, $file, $name, $values);
		}
	}
	
	public function show() {
		throw new UnprocessableEntityHttpException($this->error);
	}
	
	public function add($field, $fileOrMessage, $name = null, $values = []) {
		if(!empty($name)) {
			$message = Yii::t($fileOrMessage, $name, $values);
		} else {
			$message = $fileOrMessage;
		}
		$this->error[] = [
			'field' => $field,
			'message' => $message,
		];
		return $this;
	}
	
	public function has() {
		return !empty($this->error);
	}
	
	public function count() {
		return count($this->error);
	}
	
	public function all() {
		return $this->error;
	}
	
	public function forge($errors) {
		$this->error = $errors;
		return $this;
	}
	
	public function clear() {
		$this->error = [];
		return $this;
	}
	
}