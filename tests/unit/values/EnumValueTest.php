<?php
namespace tests\unit\values;

use yii2lab\test\Test\Unit;
use tests\_source\values\ModeEnumValue;
use yii\base\InvalidArgumentException;

class EnumValueTest extends Unit
{
	
	public function testSet()
	{
		$value = $this->buildInstance();
		$value->set(ModeEnumValue::PROD);
		$value->set(ModeEnumValue::DEV);
	}
	
	public function testSetOutRange()
	{
		$value = $this->buildInstance();
		try {
			$value->set(999);
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testDefaultValue()
	{
		$value = $this->buildInstance();
		$this->assertEquals($value->get(), ModeEnumValue::PROD);
		$this->tester->assertEquals($value->getDefault(), ModeEnumValue::PROD);
	}
	
	public function testSetAndGetValue()
	{
		$value = $this->buildInstance();
		$value->set(ModeEnumValue::DEV);
		$this->tester->assertEquals($value->get(), ModeEnumValue::DEV);
	}
	
	public function testIsValid()
	{
		$value = $this->buildInstance();
		$isValid = $value->isValid(ModeEnumValue::DEV);
		$this->tester->assertTrue($isValid);
		
		$isValid = $value->isValid(150);
		$this->tester->assertFalse($isValid);
		
		$isValid = $value->isValid('qwerty');
		$this->tester->assertFalse($isValid);
	}
	
	private function buildInstance() {
		$value = new ModeEnumValue();
		return $value;
	}
	
}
