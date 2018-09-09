<?php

namespace yii2lab\domain\factories;

use Yii;
use yii\helpers\Inflector;
use yii2lab\extension\filedb\base\FiledbActiveRecord;
use yii2lab\extension\code\helpers\generator\ClassGeneratorHelper;

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
	
	public function createVirtual($tableName, $parent = 'yii\db\ActiveRecord', $options = []) {
		$params['extends'] = $parent;
		$params['tableName'] = $params['extends'] == FiledbActiveRecord::class ? $tableName : '{{%'.$tableName.'}}';
		$params['methodName'] = $params['extends'] == FiledbActiveRecord::class ? 'fileName' : 'tableName';
		if(isset($options['primaryKey'])) {
			$params['primaryKey'] = $options['primaryKey'];
		}
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
		$code = '
			public static function '.$params['methodName'].'()
			{
				return \''.$params['tableName'].'\';
			}';
		if(!empty($params['primaryKey'])) {
			$code .= NS . NS . '
			public static function primaryKey()
			{
				return [\''.$params['primaryKey'].'\'];
			}';
		}
		if(!class_exists($class['className'])) {
			$config = $class;
			$config['use'] = [$params['extends']];
			$isFileDb = $params['extends'] == FiledbActiveRecord::class;
			$config['afterClassName'] = $isFileDb ? 'extends FiledbActiveRecord' : 'extends ActiveRecord';
			$config['code'] = $code;
			$allClassCode = ClassGeneratorHelper::generateCode($config);
			eval($allClassCode);
		}
	}
}
