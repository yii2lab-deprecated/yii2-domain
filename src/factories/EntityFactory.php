<?php

namespace common\ddd\factories;

use Yii;
use yii\helpers\ArrayHelper;

class EntityFactory extends BaseFactory {
	
	public function create($name, $params = []) {
		if($this->isClassName($name)) {
			$className = $name;
		} else {
			$className = $this->genClassName($name);
		}
		$result = [];
		$params = ArrayHelper::toArray($params);
		if(!empty($params) && ArrayHelper::isIndexed($params)) {
			foreach($params as $item) {
				$result[] = $this->createClass($className, $item);
			}
		} else {
			$result = $this->createClass($className, $params);
		}
		return $result;
	}
	
	protected function createClass($className, $params) {
		$instance = Yii::createObject($className);
		if(!empty($params)) {
			if(!is_array($params)) {
				$params = ArrayHelper::toArray($params);
			}
			$instance->load($params);
		}
		return $instance;
	}
	
}
