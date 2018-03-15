<?php
namespace tests\unit\values;

use Codeception\Test\Unit;
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
			expect(false)->true();
		} catch(InvalidArgumentException $e) {
			expect(true)->true();
		}
	}
	
	public function testDefaultValue()
	{
		$value = $this->buildInstance();
		expect($value->get())->equals(ModeEnumValue::PROD);
		expect($value->getDefault())->equals(ModeEnumValue::PROD);
	}
	
	public function testSetAndGetValue()
	{
		$value = $this->buildInstance();
		$value->set(ModeEnumValue::DEV);
		expect($value->get())->equals(ModeEnumValue::DEV);
	}
	
	public function testIsValid()
	{
		$value = $this->buildInstance();
		$isValid = $value->isValid(ModeEnumValue::DEV);
		expect($isValid)->true();
		
		$isValid = $value->isValid(150);
		expect($isValid)->false();
		
		$isValid = $value->isValid('qwerty');
		expect($isValid)->false();
	}
	
	private function buildInstance() {
		$value = new ModeEnumValue();
		return $value;
	}
	
}
