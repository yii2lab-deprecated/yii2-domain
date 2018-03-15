<?php
namespace tests\unit\values;

use Codeception\Test\Unit;
use tests\_source\values\PercentEnumValue;
use yii\base\InvalidArgumentException;

class RangeValueTest extends Unit
{
	
	public function testSet()
	{
		$value = $this->buildInstance();
		$value->set(1);
		$value->set(100);
	}
	
	public function testSetOutRange()
	{
		$value = $this->buildInstance();
		try {
			$value->set(101);
			expect(false)->true();
		} catch(InvalidArgumentException $e) {
			expect(true)->true();
		}
		try {
			$value->set(-1);
			expect(false)->true();
		} catch(InvalidArgumentException $e) {
			expect(true)->true();
		}
	}
	
	public function testDefaultValue()
	{
		$value = $this->buildInstance();
		expect($value->get())->equals(0);
		expect($value->getDefault())->equals(0);
	}
	
	public function testSetAndGetValue()
	{
		$value = $this->buildInstance();
		
		$value->set(50);
		expect($value->get())->equals(50);
		
		$value = new PercentEnumValue(51);
		expect($value->get())->equals(51);
	}
	
	public function testIsValid()
	{
		$value = $this->buildInstance();
		
		$isValid = $value->isValid(50);
		expect($isValid)->true();
		
		$isValid = $value->isValid(150);
		expect($isValid)->false();
		
		$isValid = $value->isValid('qwerty');
		expect($isValid)->false();
	}
	
	private function buildInstance() {
		$value = new PercentEnumValue();
		return $value;
	}
	
}
