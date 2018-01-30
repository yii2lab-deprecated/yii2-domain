<?php

namespace yii2lab\domain\rest;

use yii2lab\domain\traits\controller\ServiceTrait;
use yii\rest\Controller as YiiController;

class Controller extends YiiController {

	use ServiceTrait;
	
	public function format() {
		return [];
	}

	public function init() {
		parent::init();
		$this->initService();
		$this->initFormat();
	}
	
	private function initFormat() {
		$format = $this->format();
		if(empty($format)) {
			return;
		}
		$this->serializer = [
			'class' => Serializer::className(),
			'format' => $format,
		];
	}

}
