<?php

namespace yii2lab\domain\generator;

use yii2lab\designPattern\scenario\base\BaseScenario;
use yii2lab\extension\code\entities\DocBlockEntity;
use yii2lab\extension\code\helpers\ClassHelper;
use yii2lab\helpers\yii\ArrayHelper;

abstract class BaseGenerator extends BaseScenario {

	public $uses = [];
	public $defaultUses = [];
	public $docBlockParameters = [];
	public $implements;
	
	public function generate($classEntity) {
		$classEntity->doc_block = new DocBlockEntity([
			'title' => 'Class ' . $classEntity->name,
			'parameters' => $this->docBlockParameters,
		]);
		if(isset($this->implements)) {
			$classEntity->implements = $this->implements;
		}
		$uses = ArrayHelper::merge($this->defaultUses, $this->uses);
		ClassHelper::generate($classEntity, $uses);
	}
}
