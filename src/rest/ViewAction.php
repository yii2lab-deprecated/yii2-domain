<?php

namespace common\ddd\rest;

use Yii;

class ViewAction extends BaseAction {

	public $serviceMethod = 'findOne';
	
	public function run($id) {
		$params = Yii::$app->request->get();
		return $this->runServiceMethod($id, $params);
	}
}
