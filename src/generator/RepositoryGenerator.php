<?php

namespace yii2lab\domain\generator;

use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2lab\extension\code\entities\ClassEntity;
use yii2lab\extension\code\enums\AccessEnum;

class RepositoryGenerator extends BaseGenerator {

	public $name;
	public $defaultUses = [
		['name' => BaseActiveArRepository::class],
	];
	
	public function run() {
		$classEntity = new ClassEntity();
		$classEntity->name = $this->name;
		$classEntity->extends = 'BaseActiveArRepository';
		$classEntity->variables = [
			[
				'name' => 'schemaClass',
				'access' => AccessEnum::PROTECTED,
				'value' => 'true',
			],
		];
		$this->generate($classEntity);
	}
	
}
