<?php

namespace yii2lab\domain\factories;

use Yii;
use yii\base\Component;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;
use yii2lab\domain\Domain;

/**
 * Class Domain
 *
 * @package yii2lab\domain
 *
 * @property string $id
 * @property string $type
 * @property Domain $domain
 */
class BaseFactory extends Component {
	
	public $id;
	public $type;
	public $domain;
	
	public function create($name, $params = []) {
		$className = $this->genClassName($name);
		$instance = $this->createByClassName($className, $params);
		if(property_exists($instance, 'id')) {
			if(is_array($name)) {
				$instance->id = $name[ count($name) - 1 ];
			} else {
				$instance->id = $name;
			}
		}
		return $instance;
	}
	
	public function genClassName($name) {
		$className = $this->getClassName($name);
		$className .= $this->getPostfixForClassName();
		return $className;
	}
	
	protected function getClassName($name) {
		if(is_array($name)) {
			$name[ count($name) - 1 ] = Inflector::camelize($name[ count($name) - 1 ]);
			$name = implode('\\', $name);
		} else {
			$name = Inflector::camelize($name);
		}
		$dir = $this->domain->path;
		$dir .= '\\' . Inflector::pluralize($this->type);
		$className = $dir . '\\' . $name;
		return $className;
	}
	
	protected function getPostfixForClassName() {
		return Inflector::camelize($this->type);
	}
	
	protected function assignProperties($instance) {
		if(property_exists($instance, 'domain')) {
			$instance->domain = $this->domain;
		}
	}
	
	protected function createByClassName($className, $params = []) {
		if(!is_array($params)) {
			$params = ArrayHelper::toArray($params);
		}
		$instance = Yii::createObject($className);
		if(!empty($params)) {
			Yii::configure($instance, $params);
		}
		$this->assignProperties($instance);
		return $instance;
	}
	
	protected function isClassName($name) {
		return strpos($name, '\\') !== false;
	}
}