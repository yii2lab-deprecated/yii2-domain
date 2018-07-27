<?php
namespace tests\unit\values;

use yii2lab\test\Test\Unit;
use tests\_source\values\ModesEnumValue;
use yii\base\InvalidArgumentException;

class MultiEnumValueTest extends Unit
{
	
	public function testSet()
	{
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::PROD]);
		$value->set([ModesEnumValue::DEV]);
	}
	
	public function testSetOutRange()
	{
		$value = $this->buildInstance();
		try {
			$value->set([999]);
			expect(false)->true();
		} catch(InvalidArgumentException $e) {
			expect(true)->true();
		}
	}
	
	public function testDefaultValue()
	{
		$value = $this->buildInstance();
		expect($value->get())->equals([]);
		expect($value->getDefault())->equals([]);
	}
	
	public function testSetAndGetValue()
	{
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV]);
		expect($value->get())->equals([ModesEnumValue::DEV]);
	}
	
	public function testIsValid()
	{
		$value = $this->buildInstance();
		$isValid = $value->isValid([ModesEnumValue::DEV]);
		expect($isValid)->true();
		
		$value = $this->buildInstance();
		$isValid = $value->isValid([ModesEnumValue::DEV, ModesEnumValue::PROD]);
		expect($isValid)->true();
		
		$value = $this->buildInstance();
		$isValid = $value->isValid([ModesEnumValue::DEV, ModesEnumValue::PROD, ModesEnumValue::TEST]);
		expect($isValid)->true();
		
		$value = $this->buildInstance();
		$isValid = $value->isValid([ModesEnumValue::DEV, ModesEnumValue::PROD, 789]);
		expect($isValid)->false();
	}
	
	public function testAdd()
	{
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV]);
		$value->add([ModesEnumValue::PROD]);
		expect($value->get())->equals([ModesEnumValue::DEV, ModesEnumValue::PROD]);
		
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV]);
		$value->add(ModesEnumValue::PROD);
		expect($value->get())->equals([ModesEnumValue::DEV, ModesEnumValue::PROD]);
		
		$value = $this->buildInstance();
		try {
			$value->add(999);
			expect(false)->true();
		} catch(InvalidArgumentException $e) {
			expect(true)->true();
		}
	}
	
	public function testRemove()
	{
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV, ModesEnumValue::PROD, ModesEnumValue::TEST]);
		$value->remove([ModesEnumValue::PROD]);
		expect($value->get())->equals([ModesEnumValue::DEV, ModesEnumValue::TEST]);
		
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV, ModesEnumValue::PROD, ModesEnumValue::TEST]);
		$value->remove(ModesEnumValue::PROD);
		expect($value->get())->equals([ModesEnumValue::DEV, ModesEnumValue::TEST]);
		
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV, ModesEnumValue::PROD, ModesEnumValue::TEST]);
		$value = $this->buildInstance();
		try {
			$value->remove(999);
			expect(false)->true();
		} catch(InvalidArgumentException $e) {
			expect(true)->true();
		}
	}
	
	private function buildInstance() {
		$value = new ModesEnumValue();
		return $value;
	}
	
}
