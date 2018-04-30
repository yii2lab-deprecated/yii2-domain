<?php

namespace yii2lab\domain\generator;

use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\extension\code\entities\ClassEntity;

class ServiceGenerator extends BaseGenerator {

	public $name;
	public $defaultUses = [
		['name' => BaseActiveService::class],
	];
	
	public function run() {
		$classEntity = new ClassEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = 'BaseActiveService';
		$this->generate($classEntity);
	}
	
}
