<?php

namespace yii2lab\domain\generator;

use yii2lab\extension\code\entities\ClassEntity;

class EntityGenerator extends BaseGenerator {

	public $name;
	public $defaultUses = [
		['name' => 'yii2lab\domain\BaseEntity'],
	];
	
	public function run() {
		$classEntity = new ClassEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = 'BaseEntity';
		$this->generate($classEntity);
	}
	
}
