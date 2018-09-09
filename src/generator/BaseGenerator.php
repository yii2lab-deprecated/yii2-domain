<?php

namespace yii2lab\domain\generator;

use yii\base\InvalidArgumentException;
use yii2lab\extension\scenario\base\BaseScenario;
use yii2lab\extension\code\entities\ClassEntity;
use yii2lab\extension\code\entities\DocBlockEntity;
use yii2lab\extension\code\entities\InterfaceEntity;
use yii2lab\extension\code\helpers\ClassHelper;
use yii2lab\extension\yii\helpers\ArrayHelper;

abstract class BaseGenerator extends BaseScenario {

	public $uses = [];
	public $defaultUses = [];
	public $docBlockParameters = [];
	public $implements;
	
	public function generate($entity) {
		if($entity instanceof ClassEntity) {
			$typeName = 'Class';
		} elseif($entity instanceof InterfaceEntity) {
			$typeName = 'Interface';
		} else {
			throw new InvalidArgumentException('Unknown entity type');
		}
		$entity->doc_block = new DocBlockEntity([
			'title' => $typeName . SPC . $entity->name,
			'parameters' => $this->docBlockParameters,
		]);
		if(isset($this->implements)) {
			$entity->implements = $this->implements;
		}
		$uses = ArrayHelper::merge($this->defaultUses, $this->uses);
		ClassHelper::generate($entity, $uses);
	}
}
