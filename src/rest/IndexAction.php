<?php

namespace yii2lab\domain\rest;

use Yii;

class IndexAction extends BaseAction {

	public $serviceMethod = 'getDataProvider';
	
	public function run() {
		$params = Yii::$app->request->get();
		return $this->runServiceMethod($params);
	}

}
