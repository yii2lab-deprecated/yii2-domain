<?php
namespace tests\unit\values;

use yii2lab\test\Test\Unit;
use yii\base\InvalidArgumentException;
use yii2lab\domain\values\ArrayValue;

class ArrayValueTest extends Unit
{
	
	public function testSet()
	{
		$value = $this->buildInstance();
		$value->set(['qwerty'=>444]);
	}
	
	public function testSetNotValid()
	{
		$value = $this->buildInstance();
		try {
			$value->set(999);
			expect(false)->true();
		} catch(InvalidArgumentException $e) {
			expect(true)->true();
		}
	}
	
	public function testIsValid()
	{
		$value = $this->buildInstance();
		
		$isValid = $value->isValid(['qwerty'=>444]);
		expect($isValid)->true();
		
		$isValid = $value->isValid([4545]);
		expect($isValid)->true();
		
		$isValid = $value->isValid(150);
		expect($isValid)->false();
		
		$isValid = $value->isValid(null);
		expect($isValid)->false();
	}
	
	private function buildInstance() {
		$value = new ArrayValue();
		return $value;
	}
	
}
