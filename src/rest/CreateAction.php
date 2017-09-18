<?php

namespace yii2lab\domain\rest;

use Yii;

class CreateAction extends BaseAction {

	public $serviceMethod = 'create';
	public $successStatusCode = 201;
	
	public function run() {
		$body = Yii::$app->request->getBodyParams();
		return $this->runServiceMethod($body);
	}
}
