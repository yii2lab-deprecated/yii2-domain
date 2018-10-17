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
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testDefaultValue()
	{
		$value = $this->buildInstance();
		$this->tester->assertEquals($value->get(), []);
		$this->tester->assertEquals($value->getDefault(), []);
	}
	
	public function testSetAndGetValue()
	{
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV]);
		$this->tester->assertEquals($value->get(), [ModesEnumValue::DEV]);
	}
	
	public function testIsValid()
	{
		$value = $this->buildInstance();
		$isValid = $value->isValid([ModesEnumValue::DEV]);
		$this->tester->assertTrue($isValid);
		
		$value = $this->buildInstance();
		$isValid = $value->isValid([ModesEnumValue::DEV, ModesEnumValue::PROD]);
		$this->tester->assertTrue($isValid);
		
		$value = $this->buildInstance();
		$isValid = $value->isValid([ModesEnumValue::DEV, ModesEnumValue::PROD, ModesEnumValue::TEST]);
		$this->tester->assertTrue($isValid);
		
		$value = $this->buildInstance();
		$isValid = $value->isValid([ModesEnumValue::DEV, ModesEnumValue::PROD, 789]);
		$this->tester->assertFalse($isValid);
	}
	
	public function testAdd()
	{
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV]);
		$value->add([ModesEnumValue::PROD]);
		$this->tester->assertEquals($value->get(), [ModesEnumValue::DEV, ModesEnumValue::PROD]);
		
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV]);
		$value->add(ModesEnumValue::PROD);
		$this->tester->assertEquals($value->get(), [ModesEnumValue::DEV, ModesEnumValue::PROD]);
		
		$value = $this->buildInstance();
		try {
			$value->add(999);
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testRemove()
	{
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV, ModesEnumValue::PROD, ModesEnumValue::TEST]);
		$value->remove([ModesEnumValue::PROD]);
		$this->tester->assertEquals($value->get(), [ModesEnumValue::DEV, ModesEnumValue::TEST]);
		
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV, ModesEnumValue::PROD, ModesEnumValue::TEST]);
		$value->remove(ModesEnumValue::PROD);
		$this->tester->assertEquals($value->get(), [ModesEnumValue::DEV, ModesEnumValue::TEST]);
		
		$value = $this->buildInstance();
		$value->set([ModesEnumValue::DEV, ModesEnumValue::PROD, ModesEnumValue::TEST]);
		$value = $this->buildInstance();
		try {
			$value->remove(999);
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
		}
	}
	
	private function buildInstance() {
		$value = new ModesEnumValue();
		return $value;
	}
	
}
