<?php

namespace yii2lab\domain\web;

use yii2lab\domain\traits\controller\ServiceTrait;
use yii\web\Controller as YiiController;

class Controller extends YiiController {
	
	use ServiceTrait;
	
	public function init() {
		parent::init();
		$this->initService();
	}
	
}
