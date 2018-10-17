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
        $value->set([999]);
		try {
			$value->set(999);
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testIsValid()
	{
		$value = $this->buildInstance();
		
		$isValid = $value->isValid(['qwerty'=>444]);
		$this->tester->assertTrue($isValid);
		
		$isValid = $value->isValid([4545]);
		$this->tester->assertTrue($isValid);

		$isValid = $value->isValid(null);
		$this->tester->assertTrue($isValid);
	}
	
	private function buildInstance() {
		$value = new ArrayValue();
		return $value;
	}
	
}
