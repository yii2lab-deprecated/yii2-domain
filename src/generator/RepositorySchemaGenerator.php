<?php

namespace yii2lab\domain\generator;

use yii2lab\domain\repositories\relations\BaseSchema;
use yii2lab\extension\code\entities\ClassEntity;

class RepositorySchemaGenerator extends BaseGenerator {

	public $name;
	public $defaultUses = [
		['name' => BaseSchema::class],
	];
	
	public function run() {
		$classEntity = new ClassEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = 'BaseSchema';
		$classEntity->methods = [
			[
				'name' => 'relations',
			],
		];
		$this->generate($classEntity);
	}
	
}
