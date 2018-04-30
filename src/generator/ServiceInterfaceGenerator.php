<?php

namespace yii2lab\domain\generator;

use yii2lab\domain\interfaces\services\CrudInterface;
use yii2lab\extension\code\entities\InterfaceEntity;

class ServiceInterfaceGenerator extends BaseGenerator {

	public $name;
	public $defaultUses = [
		['name' => CrudInterface::class],
	];
	
	public function run() {
		$classEntity = new InterfaceEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = 'CrudInterface';
		$this->generate($classEntity);
	}
	
}
