<?php

namespace yii2lab\domain\generator;

use yii\helpers\Inflector;
use yii2lab\extension\code\helpers\ClassHelper;

class MessageGenerator extends BaseGenerator {

	public $name;
	
	public function run() {
		ClassHelper::generatePhpData($this->name, [
			'title' => Inflector::humanize(basename($this->name)),
		]);
	}
	
}
