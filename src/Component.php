<?php

namespace yii2lab\domain;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidCallException;
use yii\base\UnknownPropertyException;

class Component extends \yii\base\Component {

	public function __get($name) {
		$getter = $this->magicMethodName($name, 'get');
		if(method_exists($this, $getter)) {
			// read property, e.g. getName()
			return $this->$getter();
		}
		
		if(property_exists($this, $name)) {
			
			// read property, e.g. getName()
			return $this->$name;
		}
		
		// behavior property
		$this->ensureBehaviors();
		foreach($this->_behaviors as $behavior) {
			if($behavior->canGetProperty($name)) {
				return $behavior->$name;
			}
		}
		
		if(method_exists($this, 'set' . $name)) {
			throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
		}
		
		throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
	}
	
	public function __set($name, $value) {
		$setter = $this->magicMethodName($name, 'set');
		if(method_exists($this, $setter)) {
			// set property
			$this->$setter($value);
			
			return;
		} elseif(strncmp($name, 'on ', 3) === 0) {
			// on event: attach event handler
			$this->on(trim(substr($name, 3)), $value);
			
			return;
		} elseif(strncmp($name, 'as ', 3) === 0) {
			// as behavior: attach behavior
			$name = trim(substr($name, 3));
			$this->attachBehavior($name, $value instanceof Behavior ? $value : Yii::createObject($value));
			
			return;
		}
		
		if(property_exists($this, $name)) {
			
			// read property, e.g. getName()
			return $this->$name = $value;
		}
		
		// behavior property
		$this->ensureBehaviors();
		foreach($this->_behaviors as $behavior) {
			if($behavior->canSetProperty($name)) {
				$behavior->$name = $value;
				return;
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
		foreach($this->_behaviors as $behavior) {
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
			return;
		}
		
		// behavior property
		$this->ensureBehaviors();
		foreach($this->_behaviors as $behavior) {
			if($behavior->canSetProperty($name)) {
				$behavior->$name = null;
				return;
			}
		}
		
		throw new InvalidCallException('Unsetting an unknown or read-only property: ' . get_class($this) . '::' . $name);
	}
	
	protected function magicMethodName($name, $direction) {
		return $direction . str_replace('_', '', $name);
	}
	
}
