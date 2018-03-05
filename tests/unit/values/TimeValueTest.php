<?php
namespace tests\unit\values;

use Codeception\Test\Unit;
use DateTime;
use yii2lab\domain\values\TimeValue;

class TimeValueTest extends Unit
{
	
	const DATE_TIME_API = '2012-06-24T05:34:56Z';
	const DATE_API = '2012-06-24T00:00:00Z';
	const TIME_API = '1970-01-01T05:34:56Z';
	
	public function testSet()
	{
		$value = $this->buildInstance();
		$value->set(TIMESTAMP);
	}
	
	public function testSetString()
	{
		$value = $this->buildInstance();
		$value->set('24 Jun 2012 05:34:56 -0400');
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_TIME_API);
		
		$value = $this->buildInstance();
		$value->set('6/24/2012 5:34:56 AM');
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_TIME_API);
		
		$value = $this->buildInstance();
		$value->set('now');
		expect($value->getInFormat())->greaterOrEquals(TIMESTAMP);
		
		$value = $this->buildInstance();
		$value->set('24 Jun 2012');
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_API);
		
		$value = $this->buildInstance();
		$value->set(self::DATE_TIME_API);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_TIME_API);
	}
	
	public function testSetTime()
	{
		$value = $this->buildInstance();
		$value->setDate(1970, 1, 1);
		$value->setTime(5, 34, 56);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::TIME_API);
	}
	
	public function testSetDate()
	{
		$value = $this->buildInstance();
		$value->setDate(2012, 6, 24);
		$value->setTime(0, 0, 0);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_API);
	}
	
	public function testSetDateTime()
	{
		$value = $this->buildInstance();
		$value->setDateTime(2012, 6, 24, 5, 34, 56);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_TIME_API);
	}
	
	public function testSetArray()
	{
		$value = $this->buildInstance();
		$value->set([2012, 6, 24, 5, 34, 56]);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_TIME_API);
	}
	
	public function testSetInteger()
	{
		$value = $this->buildInstance();
		$value->set(1340516096);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_TIME_API);
		
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
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_TIME_API);
	}
	
	public function testSetFromFormat()
	{
		$value = $this->buildInstance();
		$value->setFromFormat('24/06/2012 05:34:56', 'd/m/Y H:i:s');
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_TIME_API);
		
		$value = $this->buildInstance();
		$value->setFromFormat(self::DATE_TIME_API, TimeValue::FORMAT_API);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_TIME_API);
	}
	
	public function testFormat()
	{
		$value = $this->buildInstance();
		$value->setDateTime(2012, 6, 24, 5, 34, 56);
		expect($value->getInFormat(TimeValue::FORMAT_API))->equals(self::DATE_TIME_API);
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
