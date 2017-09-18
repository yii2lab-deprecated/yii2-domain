<?php

namespace common\ddd\rest;

use Yii;

class UpdateAction extends BaseAction {

	public $serviceMethod = 'update';
	public $successStatusCode = 204;
	
	public function run($id) {
		$body = Yii::$app->request->getBodyParams();
		return $this->runServiceMethod($id, $body);
	}
}
