<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii2lab\domain\enums\TypeErrorEnum;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\extension\common\helpers\ClassHelper;

class ErrorCollection
{

	protected $error = [];

	public function __construct($field = null, $file = null, $name = null, $values = [], $line = null, $errorCode = TypeErrorEnum::ERROR_UNKNOWN_ERROR)
	{
		if (func_num_args() >= 2) {
			$this->add($field, $file, $name, $values, $line, $errorCode);
		}
	}

	public function show()
	{
		throw new UnprocessableEntityHttpException($this->error);
	}

	public function add($target, $fileOrMessage, $name = null, $values = [], $line = null, $errorCode = TypeErrorEnum::ERROR_UNKNOWN_ERROR)
	{
		if (!empty($name)) {
			$message = Yii::t($fileOrMessage, $name, $values);
		} else {
			$message = $fileOrMessage;
		}
		if (!empty($line)) {
			$target = $target . ': ' . $line;
		}
		$this->error[] = [
			'field' => $target,
			'message' => $message,
			'error_code' => $errorCode,

		];
		return $this;
	}

	public function has()
	{
		return !empty($this->error);
	}

	public function count()
	{
		return count($this->error);
	}

	public function all()
	{
		return $this->error;
	}


	public function clear()
	{
		$this->error = [];
		return $this;
	}

	public static function forge($field, $message, $name = null, $values = [], $line = null,  ErrorCollection $collection = null, $errorCode = TypeErrorEnum::ERROR_UNKNOWN_ERROR)
	{
		if ($collection) {
			$collection->add($field,  $message, $name, $values, $line, $errorCode);
		}
		return new ErrorCollection($field, $message, $name, $values, $line, $errorCode);
	}

}