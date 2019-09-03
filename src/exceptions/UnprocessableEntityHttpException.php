<?php

namespace yii2lab\domain\exceptions;


use yii\base\InvalidArgumentException;
use yii2lab\domain\helpers\ErrorCollection;
use Exception;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii2lab\extension\common\helpers\ClassHelper;
use yii2module\error\domain\helpers\UnProcessibleHelper;

class UnprocessableEntityHttpException extends HttpException {
	
	private $errors = [];
	
	public function __construct($errors, $code = 0, Exception $previous = null) {
		$message = '';
		if (!empty($errors)) {
			if (is_string($errors)) {
				$message = $errors;
				$errors = ErrorCollection::forge(ClassHelper::getClassOfClassName(parent::getFile()), parent::getLine(),  $message);
				if($code == 1){
					$errors = ErrorCollection::forge('system error',  $message);
				}

			} elseif ($errors instanceof ErrorCollection) {
				$errors = UnProcessibleHelper::assoc2indexed($errors);
				$message = json_encode(ArrayHelper::toArray($errors));
			}
		}
		parent::__construct(422, $message, $code, $previous);
		$this->setErrors($errors);
	}
	
	public function getErrors() {
		return $this->errors;
	}
	
	private function setErrors($errors) {
		if ($errors instanceof ErrorCollection) {
			$errors = $errors->all();
		}
		if(empty($errors)){
			throw new InvalidArgumentException("error collection is empty");
		}
		$this->errors = $errors;
	}
}
