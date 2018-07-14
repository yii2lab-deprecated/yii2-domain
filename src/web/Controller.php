<?php

namespace yii2lab\domain\web;

use yii\web\Controller as YiiController;
use yii2lab\extension\web\helpers\ControllerHelper;

class Controller extends YiiController {
	
	public $service = null;
	
	public function init() {
		parent::init();
		$this->service = ControllerHelper::forgeService($this->service);
	}
	
}
