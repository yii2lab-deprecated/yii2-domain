<?php

namespace yii2lab\domain\generator;

use yii2lab\extension\code\helpers\ClassHelper;

class MessageGenerator extends BaseGenerator {

	public $name;
	
	public function run() {
		ClassHelper::generatePhpData($this->name, []);
	}
	
}
