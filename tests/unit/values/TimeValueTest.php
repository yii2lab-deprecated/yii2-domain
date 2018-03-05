<?php
namespace tests\unit\values;

use Codeception\Test\Unit;
use DateTime;
use yii2lab\domain\values\TimeValue;

class TimeValueTest extends Unit
{
	
	public function testSet()
	{
		$value = $this->buildInstance();
		$value->set(TIMESTAMP);
	}
	
	public function testSetString()
	{
		$value = $this->buildInstance();
		$value->set('5 Mar 2018 05:34:56 -0400');
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2018-03-05T05:34:56Z');
		
		$value = $this->buildInstance();
		$value->set('3/5/2018 5:34:56 AM');
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2018-03-05T05:34:56Z');
		
		$value = $this->buildInstance();
		$value->set('now');
		expect($value->getInFormat())->greaterOrEquals(TIMESTAMP);
		
		$value = $this->buildInstance();
		$value->set('10 September 2000');
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2000-09-10T00:00:00Z');
	}
	
	public function testSetTime()
	{
		$value = $this->buildInstance();
		$value->setTime(5, 34, 56);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2018-03-05T05:34:56Z');
	}
	
	public function testSetDate()
	{
		$value = $this->buildInstance();
		$value->setDate(2012, 6, 24);
		$value->setTime(0, 0, 0);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2012-06-24T00:00:00Z');
	}
	
	public function testSetDateTime()
	{
		$value = $this->buildInstance();
		$value->setDateTime(2012, 6, 24, 5, 34, 56);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2012-06-24T05:34:56Z');
	}
	
	public function testSetArray()
	{
		$value = $this->buildInstance();
		$value->set([2012, 6, 24, 5, 34, 56]);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2012-06-24T05:34:56Z');
	}
	
	public function testSetInteger()
	{
		$value = $this->buildInstance();
		$value->set(1340516096);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2012-06-24T05:34:56Z');
		
		$value = $this->buildInstance();
		$value->set(-12345);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('1969-12-31T20:34:15Z');
	}
	
	public function testSetObject()
	{
		$dateTime = new DateTime();
		$dateTime->setTimestamp(1340516096);
		$value = $this->buildInstance();
		$value->set($dateTime);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2012-06-24T05:34:56Z');
	}
	
	public function testSetFromFormat()
	{
		$value = $this->buildInstance();
		$value->setFromFormat('25/12/2013 10:13:46', 'd/m/Y H:i:s');
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2013-12-25T10:13:46Z');
	}
	
	public function testFormat()
	{
		$value = $this->buildInstance();
		$value->setDateTime(2012, 6, 24, 5, 34, 56);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals('2012-06-24T05:34:56Z');
		expect($value->getInFormat())->equals(1340516096);
		expect($value->getInFormat(TimeValue::FORMAT_WEB))->equals('2012-06-24 05:34:56');
		expect($value->getInFormat(TimeValue::FORMAT_WEB_DATE))->equals('2012-06-24');
		expect($value->getInFormat(TimeValue::FORMAT_WEB_TIME))->equals('05:34:56');
	}
	
	public function testDefaultValue()
	{
		$value = $this->buildInstance();
		expect($value->get()->getTimestamp())->equals(TIMESTAMP);
		expect($value->getDefault()->getTimestamp())->equals(TIMESTAMP);
	}
	
	public function testSetAndGetValue()
	{
		$value = $this->buildInstance();
		
		$value->set(50);
		expect($value->get()->getTimestamp())->equals(50);
		
		$value = new TimeValue(51);
		expect($value->get()->getTimestamp())->equals(51);
	}
	
	public function testIsValid()
	{
		$value = $this->buildInstance();
		
		$isValid = $value->isValid(50);
		expect($isValid)->true();
		
		$isValid = $value->isValid(150);
		expect($isValid)->true();
		
		$isValid = $value->isValid(-150);
		expect($isValid)->true();
		
		$isValid = $value->isValid('qwerty');
		expect($isValid)->false();
	}
	
	private function buildInstance() {
		$value = new TimeValue();
		return $value;
	}
	
}
