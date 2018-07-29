<?php

namespace yii2lab\domain;

use yii\base\InvalidArgumentException;
use yii\base\ModelEvent;
use yii2lab\domain\helpers\EntityType;
use yii2lab\domain\helpers\Helper;
use yii2lab\helpers\ReflectionHelper;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use ReflectionClass;
use ReflectionProperty;
use yii\base\Arrayable;
use yii\helpers\ArrayHelper;
use yii2lab\validator\DynamicModel;

// todo: implement Model interfaces

class BaseEntity extends Component implements Arrayable {
	
	const EVENT_INIT = 'init';
	
	private $old_attributes = [];
	private $isNew = true;
	
	public function fieldType() {
		return [];
	}
	
	public function extraFields() {
		return [];
	}
	
	public function rules() {
		return [];
	}
	
	/*public static function primaryKey() {
		return [];
	}*/
	
	public function init()
	{
		parent::init();
		$this->trigger(self::EVENT_INIT);
	}
	
	public function fields() {
		$fields = $this->attributes();
		$fields = array_diff($fields, $this->extraFields());
		return array_combine($fields, $fields);
	}
	
	public function __construct($config = [], $isNew = true) {
		if(!empty($config)) {
			if($config instanceof BaseEntity) {
				$config = $config->toArray();
			}
			$this->old_attributes = $this->setAttributes($config);
		}
		$this->isNew = $isNew;
		$this->init();
	}
	
	public function validate() {
		$form = new DynamicModel();
		$form->loadRules($this->rules());
		$form->loadData($this->toArray());
		if(!$form->validate()) {
			throw new UnprocessableEntityHttpException($form);
		}
	}
	
	/*public function getIsNew() {
		return $this->isNew;
	}*/
	
	public function getConstantEnum($prefix = null) {
		$enums = ReflectionHelper::getConstantsValuesByPrefix($this, $prefix);
		return $enums;
	}
	
	/*public function getPrimaryKey($asArray = false) {
		$keys = $this->primaryKey();
		$attributes = $this->toArray();
		if(!$asArray && count($keys) === 1) {
			return isset($attributes[ $keys[0] ]) ? $attributes[ $keys[0] ] : null;
		} else {
			$values = [];
			foreach($keys as $name) {
				$values[ $name ] = isset($attributes[ $name ]) ? $attributes[ $name ] : null;
			}
			return $values;
		}
	}*/
	
	public function toArrayRaw(array $fields = [], array $expand = [], $recursive = true) {
		return $this->toArray($fields, $expand, $recursive, true);
	}
	
	public function toArray(array $fields = [], array $expand = [], $recursive = true, $isRaw = false) {
		if(empty($fields)) {
			$fields = $this->fields();
		}
		$fields = $this->addExtraFields($fields, $expand);
		$result = [];
		foreach($fields as $name) {
			$value = $this->getAttribute($name, $isRaw);
			$result[ $name ] = Helper::toArray($value);
		}
		return $result;
	}
	
	private function addExtraFields($fields, $expand) {
		$extra = $this->extraFields();
		if(empty($extra)) {
			return $fields;
		}
		foreach($expand as $field) {
			if(in_array($field, $extra)) {
				$fields[ $field ] = $field;
			}
		}
		return $fields;
	}
	
	public function load($attributes, $only = null) {
		//$attributes = ArrayHelper::toArray($attributes);
		$this->setAttributes($attributes, $only);
	}
	
	private function setAttributes($values, $attributeNames = null) {
		if(empty($values) || !is_array($values)) {
			return null;
		}
		$old_attributes = [];
		if(empty($attributeNames)) {
			$attributeNames = $this->attributes();
		}
		foreach($values as $name => $value) {
			if(in_array($name, $attributeNames)) { //isset($values[$name]) &&
				//$value = Helper::toArray($values[ $name ]);
				$old_attributes[ $name ] = $this->setFieldValue($name, $value);
			}
		}
		return $old_attributes;
	}
	
	public static function attributes() {
		$class = new ReflectionClass(static::class);
		$propertyTypes = ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED;
		$properties = $class->getProperties($propertyTypes);
		$names = [];
		foreach($properties as $property) {
			$names[] = $property->getName();
		}
		return $names;
	}
	
	private function setFieldValue($name, $value) {
		$event = new ModelEvent();
		$this->trigger(self::EVENT_SET_ATTRIBUTE, $event);
		$method = $this->magicMethodName($name, 'set');
		if(method_exists($this, $method)) {
			$this->$method($value);
		} else {
			$fieldType = $this->fieldType();
			
			//$typesFromRules = $this->getTypesFromRules();
			
			//prr($typesFromRules);
			if(!empty($fieldType[ $name ])) {
				$this->$name = EntityType::encode($value, $fieldType[ $name ]);
			} else {
				$this->$name = $value;
			}
		}
		return $this->$name;
	}

}