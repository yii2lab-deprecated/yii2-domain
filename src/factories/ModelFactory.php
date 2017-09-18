<?php

namespace common\ddd\factories;

use Yii;

class ModelFactory extends BaseFactory {
	
	public function create($name, $params = []) {
		if($this->isClassName($name)) {
			$className = $name;
		} else {
			$className = $this->genClassName($name);
		}
		$instance = Yii::createObject($className);
		return $instance;
	}
	
	public function genClassName($name) {
		$className = $this->getClassName($name);
		return $className;
	}
	
}
