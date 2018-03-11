<?php

namespace yii2lab\domain\factories;

use Yii;
use yii\helpers\Inflector;
use yii2lab\helpers\generator\ClassGeneratorHelper;

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
	
	public function createVirtual($tableName, $parent = 'yii\db\ActiveRecord') {
		$params['extends'] = $parent;
		$params['tableName'] = $params['extends'] == 'yii2tech\filedb\ActiveRecord' ? $tableName : '{{%'.$tableName.'}}';
		$params['methodName'] = $params['extends'] == 'yii2tech\filedb\ActiveRecord' ? 'fileName' : 'tableName';
		
		$class = $this->genClassNameVirtual($tableName);
		$this->defineClassVirtual($class, $params);
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
	
	private function defineClassVirtual($class, $params = []) {
		if(!class_exists($class['className'])) {
			$classCode = '
	public static function '.$params['methodName'].'()
	{
		return \''.$params['tableName'].'\';
	}
';
			$config = $class;
			$config['use'] = [$params['extends']];
			$config['afterClassName'] = 'extends ActiveRecord';
			$config['code'] = $classCode;
			$allClassCode = ClassGeneratorHelper::generateCode($config);
			eval($allClassCode);
		}
	}
}
