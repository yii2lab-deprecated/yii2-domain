<?php

namespace yii2lab\domain\values;

use DateTime;

class TimeValue extends BaseValue {
	
	const FORMAT_WEB = 'Y-m-d H:i:s';
	const FORMAT_WEB_TIME = 'H:i:s';
	const FORMAT_WEB_DATE = 'Y-m-d';
	const FORMAT_API = 'Y-m-d\TH:i:s\Z';
	const TIMESTAMP = '_TIMESTAMP_';
	
	public function setDateTime($year = 0, $month = 0, $day = 0, $hour = 0, $minute = 0, $second = 0) {
		$this->setDate($year, $month, $day);
		$this->setTime($hour, $minute, $second);
	}
	
	public function setTime($hour = 0, $minute = 0, $second = 0) {
		/** @var DateTime $dateTime */
		$dateTime = $this->get();
		$dateTime->setTime($hour, $minute, $second);
		$this->set($dateTime);
	}
	
	public function setDate($year = 0, $month = 0, $day = 0) {
		/** @var DateTime $dateTime */
		$dateTime = $this->get();
		$dateTime->setDate($year, $month, $day);
		$this->set($dateTime);
	}
	
	public function setFromFormat($value, $format) {
		/** @var DateTime $dateTime */
		$dateTime = DateTime::createFromFormat($format, $value);
		$this->set($dateTime);
	}
	
	public function setNow() {
		$this->setFromFormat(TIMESTAMP, TimeValue::TIMESTAMP);
	}
	
	public function getInFormat($mask = self::TIMESTAMP) {
		$dateTime = $this->get();
		if($mask == self::TIMESTAMP) {
			$value = $dateTime->getTimestamp();
		} else {
			$value = $dateTime->format($mask);
		}
		return $value;
	}
	
	protected function _encode($value) {
		/** @var DateTime $dateTime */
		if($value instanceof DateTime) {
			$dateTime = $value;
		} else {
			$dateTime = new DateTime();
		}
		if(is_integer($value)) {
			$dateTime->setTimestamp($value);
		}
		if(is_array($value)) {
			$dateTime->setDate($value[0], $value[1], $value[2]);
			$dateTime->setTime($value[3], $value[4], $value[5]);
		}
		if(is_string($value)) {
			$dateTime = new DateTime($value);
		}
		return $dateTime;
	}
	
	public function getDefault() {
		return $this->_encode(TIMESTAMP);
	}
	
	public function isValid($value) {
		try {
			$dateTime = $this->_encode($value);
		} catch(\Exception $e) {
			return false;
		}
		return !empty($dateTime->getTimestamp());
	}
}
