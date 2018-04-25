<?php

namespace yii2lab\domain\exceptions;

use yii\web\BadRequestHttpException;

class BadQueryHttpException extends BadRequestHttpException {

	public function getName() {
		return 'Bad query';
	}
	
	public function __construct(?string $message = null, int $code = 0, \Exception $previous = null) {
		$message = $message ?: 'Bad query parameters';
		parent::__construct($message, $code, $previous);
	}
	
}
