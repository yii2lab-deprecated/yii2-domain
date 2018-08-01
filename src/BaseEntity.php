<?php

namespace yii2lab\domain;

use yii\base\InvalidCallException;
use yii\base\Event;
use yii\base\InvalidArgumentException;
use yii\base\ModelEvent;
use yii2lab\domain\exceptions\ReadOnlyException;
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
    private $modifiedAttributes = [];
	private $isNew = true;

	public function fieldType() {
		return [];
	}

    public function readOnlyFields() {
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
			$this->setAttributes($config);
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

    public function modifiedFields() {
        if(empty($attributeNames)) {
            $attributeNames = $this->attributes();
        }
        $result = [];
        foreach($attributeNames as $name) {
            if(array_key_exists($name, $this->old_attributes)) {
                if($this->{$name} !== $this->old_attributes[$name]) { //isset($values[$name]) &&
                    $result[] = $name;
                }
            }
        }
        return $result;
    }

	private function setAttributes($values, $attributeNames = null) {
		if(empty($values) || !is_array($values)) {
			return null;
		}
		if(empty($attributeNames)) {
			$attributeNames = $this->attributes();
		}
        $this->old_attributes = [];
		foreach($values as $name => $value) {
			if(in_array($name, $attributeNames)) {
				//$this->setFieldValue($name, $value);
                $this->__set($name, $value);
			}
		}
        foreach($attributeNames as $name) {
            if(isset($this->$name)) {
                $this->old_attributes[ $name ] = $this->$name;
            }
        }
		return $this->old_attributes;
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

    protected function isReadOnly($name) {
        $readOnly = $this->readOnlyFields();
	    if(empty($readOnly)) {
	        return false;
        }
        if(!in_array($name, $readOnly)) {
            return false;
        }
        //$modifiedFields = $this->modifiedFields();
        if(!empty($this->$name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
            // ReadOnlyException
        }
	    return true;
    }

	protected function evaluteFieldValue($name, $value) {
	   /*$modifiedFields = $this->modifiedFields();
	    if($this->isReadOnly($name) && in_array($name, $modifiedFields)) {
	        throw new ReadOnlyException('Field "' . $name . '" is read only');
        }*/
        //$event->data = $value;
        //$this->trigger(self::EVENT_SET_ATTRIBUTE);

        $fieldType = $this->fieldType();

        //$typesFromRules = $this->getTypesFromRules();

        //prr($typesFromRules);
        if (empty($fieldType[$name])) {
            return $value;
        }
        return EntityType::encode($value, $fieldType[$name]);
	}

}