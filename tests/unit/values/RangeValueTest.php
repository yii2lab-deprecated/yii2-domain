<?php
namespace tests\unit\values;

use yii2lab\test\Test\Unit;
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
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
		}
		try {
			$value->set(-1);
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testDefaultValue()
	{
		$value = $this->buildInstance();
		$this->tester->assertEquals($value->get(), 0);
		$this->tester->assertEquals($value->getDefault(), 0);
	}
	
	public function testSetAndGetValue()
	{
		$value = $this->buildInstance();
		
		$value->set(50);
		$this->assertEquals($value->get(), 50);
		
		$value = new PercentEnumValue(51);
		$this->tester->assertEquals($value->get(), 51);
	}
	
	public function testIsValid()
	{
		$value = $this->buildInstance();
		
		$isValid = $value->isValid(50);
		$this->tester->assertTrue($isValid);
		
		$isValid = $value->isValid(150);
		$this->tester->assertFalse($isValid);
		
		$isValid = $value->isValid('qwerty');
		$this->tester->assertFalse($isValid);
	}
	
	private function buildInstance() {
		$value = new PercentEnumValue();
		return $value;
	}
	
}
