<?php

namespace yii2lab\domain\factories;

use Yii;
use yii\helpers\Inflector;

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
	
	public function createVirtual($tableName) {
		$class = $this->genClassNameVirtual($tableName);
		$this->defineClassVirtual($class, $tableName);
		$class['model'] = Yii::createObject($class['className']);
		return $class;
	}
	
	public function genClassName($name) {
		$className = $this->getClassName($name);
		return $className;
	}
	
	private function genClassNameVirtual($tableName) {
		$modelClass = Inflector::camelize($tableName) . 'Model';
		$namespace = str_replace('/', '\\', $this->domain->path) . '\\models';
		$className = $namespace . '\\' . $modelClass;
		return [
			'namespace' => $namespace,
			'baseName' => $modelClass,
			'className' => $className,
		];
	}
	
	private function defineClassVirtual($class, $tableName) {
		if(!class_exists($class['className'])) {
			$classCode = '
namespace '.$class['namespace'].';

use yii\db\ActiveRecord;

class '.$class['baseName'].' extends ActiveRecord  {
	
	public static function tableName()
	{
		return \'{{%'.$tableName.'}}\';
	}
	
}';
			eval($classCode);
		}
	}
}
