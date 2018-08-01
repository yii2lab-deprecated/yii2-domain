<?php

namespace yii2lab\domain;

use DateTime;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidCallException;
use yii\base\ModelEvent;
use yii\base\UnknownPropertyException;
use yii2lab\domain\values\BaseValue;
use yii2lab\domain\values\TimeValue;

class Component extends \yii\base\Component {
	
	const EVENT_SET_ATTRIBUTE = 'set_attribute';
	const EVENT_GET_ATTRIBUTE = 'get_attribute';
	
	protected function extractValue($value, $inRaw = false) {
		if($inRaw) {
			return $value;
		}
		if($value instanceof BaseValue) {
			$value = $value->get();
		}
		if($value instanceof DateTime) {
			$value = $value->format(TimeValue::FORMAT_WEB);
		}
		return $value;
	}

    protected function getAttribute($name, $inRaw = false) {
		$getter = $this->magicMethodName($name, 'get');
		if(method_exists($this, $getter)) {
			// read property, e.g. getName()
			return $this->extractValue($this->$getter(), $inRaw);
		}
		
		if(property_exists($this, $name)) {
			
			// read property, e.g. getName()
			return $this->extractValue($this->$name, $inRaw);
		}
		
		// behavior property
		$this->ensureBehaviors();
		foreach($this->behaviors as $behavior) {
			if($behavior->canGetProperty($name)) {
				return $this->extractValue($behavior->$name, $inRaw);
			}
		}
		
		if(method_exists($this, 'set' . $name)) {
			throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
		}
		
		throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
	}
	
	public function __get($name) {
		return $this->getAttribute($name);
	}
	
	public function __set($name, $value) {
        $this->isReadOnly($name);
	    $this->trigger(self::EVENT_SET_ATTRIBUTE);
		$setter = $this->magicMethodName($name, 'set');
		if(method_exists($this, $setter)) {
			// set property
			$this->$setter($value);
			
			return null;
		} elseif(strncmp($name, 'on ', 3) === 0) {
			// on event: attach event handler
			$this->on(trim(substr($name, 3)), $value);
			
			return null;
		} elseif(strncmp($name, 'as ', 3) === 0) {
			// as behavior: attach behavior
			$name = trim(substr($name, 3));
			$this->attachBehavior($name, $value instanceof Behavior ? $value : Yii::createObject($value));
			
			return null;
		}
		
		if(property_exists($this, $name)) {

            return $this->$name = $this->evaluteFieldValue($name, $value);

			// read property, e.g. getName()
			//$value;
		}
		
		// behavior property
		$this->ensureBehaviors();
		foreach($this->behaviors as $behavior) {
			if($behavior->canSetProperty($name)) {
				$behavior->$name = $value;
				return null;
			}
		}
		
		if(method_exists($this, 'get' . $name)) {
			throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
		}
		
		throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
	}
	
	public function __isset($name) {
		$getter = $this->magicMethodName($name, 'get');
		if(method_exists($this, $getter)) {
			return $this->$getter() !== null;
		}
		
		// behavior property
		$this->ensureBehaviors();
		foreach($this->behaviors as $behavior) {
			if($behavior->canGetProperty($name)) {
				return $behavior->$name !== null;
			}
		}
		
		return false;
	}
	
	public function __unset($name) {
		$setter = $this->magicMethodName($name, 'set');
		if(method_exists($this, $setter)) {
			$this->$setter(null);
			return null;
		}
		
		// behavior property
		$this->ensureBehaviors();
		foreach($this->behaviors as $behavior) {
			if($behavior->canSetProperty($name)) {
				$behavior->$name = null;
				return null;
			}
		}
		
		throw new InvalidCallException('Unsetting an unknown or read-only property: ' . get_class($this) . '::' . $name);
	}
	
	protected function magicMethodName($name, $direction) {
		return $direction . str_replace('_', '', $name);
	}
	
}
