<?php
namespace tests\unit\values;

use Codeception\Test\Unit;
use yii\base\InvalidArgumentException;
use yii2lab\domain\values\StringValue;

class StringValueTest extends Unit
{
	
	public function testSet()
	{
		$value = $this->buildInstance();
		$value->set('qwerty');
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
		$isValid = $value->isValid('qwerty');
		expect($isValid)->true();
		
		$isValid = $value->isValid(150);
		expect($isValid)->false();
		
		$isValid = $value->isValid([4545]);
		expect($isValid)->false();
		
		$isValid = $value->isValid(null);
		expect($isValid)->false();
	}
	
	private function buildInstance() {
		$value = new StringValue();
		return $value;
	}
	
}
