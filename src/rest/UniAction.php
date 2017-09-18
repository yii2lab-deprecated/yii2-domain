<?php

namespace yii2lab\domain\rest;

use Yii;

class UniAction extends BaseAction {

	public $serviceMethod = 'update';
	
	public function run() {
		$body = Yii::$app->request->getBodyParams();
		$response = $this->runServiceMethod($body);
		return $this->responseToArray($response);
	}

}
