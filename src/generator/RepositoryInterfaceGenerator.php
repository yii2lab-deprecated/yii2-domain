<?php

namespace yii2lab\domain\generator;

use yii2lab\domain\interfaces\repositories\CrudInterface;
use yii2lab\extension\code\entities\InterfaceEntity;

class RepositoryInterfaceGenerator extends BaseGenerator {

	public $name;
	public $extends = 'CrudInterface';
	public $defaultUses = [
		['name' => CrudInterface::class],
	];
	
	public function run() {
		$classEntity = new InterfaceEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = $this->extends;
		$this->generate($classEntity);
	}
	
}
