<?php

namespace yii2lab\domain\generator;

use yii2lab\extension\code\entities\CodeEntity;
use yii2lab\extension\code\helpers\ClassHelper;
use yii2lab\helpers\yii\FileHelper;

class MessageGenerator extends BaseGenerator {

	public $name;
	
	public function run() {
		$codeEntity = new CodeEntity();
		$codeEntity->code = 'return [];';
		$pathName = FileHelper::getPath('@' . $this->name);
		FileHelper::save($pathName . DOT . 'php', ClassHelper::renderPhp($codeEntity));
	}
	
}
