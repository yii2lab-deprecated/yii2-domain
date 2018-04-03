<?php

namespace yii2lab\domain;

use yii2lab\domain\helpers\EntityType;
use yii2lab\domain\helpers\Helper;
use yii2lab\domain\values\BaseValue;
use yii2lab\helpers\ReflectionHelper;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use ReflectionClass;
use ReflectionProperty;
use yii\base\Arrayable;
use yii\helpers\ArrayHelper;
use yii2lab\validator\DynamicModel;

class BaseEntity extends Component implements Arrayable {
	
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
	
	public function hideIfNullFields() {
		$fields = [];
		$fieldType = $this->fieldType();
		foreach($fieldType as $fieldName => $fieldConfig) {
			if(!empty($fieldConfig['isHideIfNull'])) {
				$fields[] = $fieldName;
			}
		}
		return $fields;
	}
	
	public static function primaryKey() {
		return [];
	}
	
	public function init() {
	}
	
	public function fields() {
		$fields = $this->attributes();
		$fields = array_diff($fields, $this->extraFields());
		return array_combine($fields, $fields);
	}
	
	public function __construct($config = [], $isNew = true) {
		if(!empty($config)) {
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
	
	public function getIsNew() {
		return $this->isNew;
	}
	
	public function getConstantEnum($prefix = null) {
		$enums = ReflectionHelper::getConstantsValuesByPrefix($this, $prefix);
		return $enums;
	}
	
	public function getPrimaryKey($asArray = false) {
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
	}
	
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
			$isHide = $value === null && $this->isInHiddenFieldOnNull($name);
			if(!$isHide) {
				$result[ $name ] = Helper::toArray($value);
			}
		}
		return $result;
	}
	
	protected function forgeEntity($value, $className) {
        if(!empty($value) && ! $value instanceof BaseEntity) {
            $value = ArrayHelper::toArray($value);
            $value = \Yii::$domain->account->factory->entity->create($className, $value);
        }
        return $value;
    }
	
	protected function addExtraFields($fields, $expand) {
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
	
	public function load($attributes) {
		$attributes = ArrayHelper::toArray($attributes);
		$this->setAttributes($attributes);
	}
	
	protected function setAttributes($values) {
		if(empty($values) || !is_array($values)) {
			return null;
		}
		$old_attributes = [];
		$attributeNames = $this->attributes();
		foreach($values as $name => $value) {
			if(in_array($name, $attributeNames)) { //isset($values[$name]) &&
				$value = Helper::toArray($values[ $name ]);
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
	
	private function isInHiddenFieldOnNull($name) {
		$hide = $this->hideIfNullFields();
		if(empty($hide)) {
			return false;
		}
		return !is_array($hide) || in_array($name, $hide);
	}
	
	private function getFieldValue($name) {
		return $this->__get($name);
	}
	
	/*private function getTypesFromRules() {
		$typesFromRules = [];
		foreach($this->rules() as $rule) {
			$values = ArrayHelper::toArray($rule[0]);
			$type = $rule[1];
			if($type == 'integer' || $type == 'boolean') {
				foreach($values as $value) {
					$typesFromRules[ $value ] = $type;
				}
			}
		}
		return $typesFromRules;
	}*/
	
	private function setFieldValue($name, $value) {
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