<?php

namespace yii2lab\domain\web;

use common\traits\controller\ServiceTrait;
use common\traits\controller\AccessTrait;
use yii\web\Controller as YiiController;

class Controller extends YiiController {
	
	use ServiceTrait;
	use AccessTrait;
	
	public function init() {
		parent::init();
		$this->initService();
	}
	
}
