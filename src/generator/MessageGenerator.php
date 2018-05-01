<?php

namespace yii2lab\domain\generator;

use yii\helpers\Inflector;
use yii2lab\extension\code\helpers\CodeHelper;

class MessageGenerator extends BaseGenerator {

	public $name;
	
	public function run() {
		CodeHelper::generatePhpData($this->name, [
			'title' => Inflector::humanize(basename($this->name)),
		]);
	}
	
}
