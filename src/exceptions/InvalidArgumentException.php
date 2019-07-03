<?php


namespace yii2lab\domain\exceptions;

use Exception;
use yii\web\HttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\extension\common\helpers\ClassHelper;
use yii2lab\extension\console\helpers\Error;
use yii2module\error\domain\helpers\UnProcessibleHelper;

class InvalidArgumentException extends HttpException
{
	private $errors = [];

	public function __construct($errors, $code = 0, Exception $previous = null, $field = null)
	{
		$message = '';
		if(empty($field)){
			$field = ClassHelper::getClassOfClassName(parent::getFile());
		}
		if (!empty($errors)) {
			if (is_string($errors)) {
				$message = $errors;
				$errors = ErrorCollection::forge($field,  $message, null, null, parent::getLine());
			} elseif ($errors instanceof ErrorCollection) {
				$errors = UnProcessibleHelper::assoc2indexed($errors);
				$message = json_encode(ArrayHelper::toArray($errors));
			}
		}
		parent::__construct(500, $message, $code, $previous);
		$this->setErrors($errors);
	}

	public function getErrors()
	{

		return $this->errors;
	}



	private function setErrors($errors)
	{
		if ($errors instanceof ErrorCollection) {
			$errors = $errors->all();
		}
		if (empty($errors)) {
			throw new InvalidArgumentException("error collection is empty");
		}
		$this->errors = $errors;
	}
}