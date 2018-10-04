<?php

namespace yii2lab\domain\enums;

use yii2lab\app\domain\helpers\EnvService;
use yii2lab\extension\enum\base\BaseEnum;

class Driver extends BaseEnum {
	
	const ACTIVE_RECORD = 'ar';
	const DISC = 'disc';
	const CORE = 'core';
	const TPS = 'tps';
	const FILE = 'file';
	const UPLOAD = 'upload';
	const FLY = 'fly';
	const SESSION = 'session';
	const REST = 'rest';
	const TEST = 'test';
	const API = 'api';
	const GATE = 'gate';
	const MEMORY = 'memory';
	const YII = 'yii';
	const MOCK = 'mock';
	const WSDL = 'wsdl';
	const HEADER = 'header';
	const COOKIE = 'cookie';
	const FILEDB = 'filedb';
	const BRIDGE = 'bridge';
    const ENV = 'env';
	
	public static function primary($withTest = false) {
		$driver = EnvService::get('domain.driver.primary');
		if($driver == self::CORE) {
			return $driver;
		}
		return self::test($driver, $withTest);
	}
	
	public static function slave($withTest = false) {
		$driver = EnvService::get('domain.driver.slave');
		return self::test($driver, $withTest);
	}
	
	/**
	 * @param bool $withTest
	 *
	 * @return array|mixed|null|string
	 *
	 * @deprecated moved to self::primary()
	 */
	public static function remote($withTest = false) {
		$driver = EnvService::get('domain.driver.primary');
		if(empty($driver)) {
			$driver = EnvService::get('remote.driver');
		}
		if($driver == self::CORE) {
			return $driver;
		}
		return self::test($driver, $withTest);
	}
	
	public static function test($driver = null, $test = self::TEST) {
		if(!YII_ENV_TEST || !$test) {
			return $driver;
		}
		$driver = is_string($test) ? $test : self::TEST;
		return $driver;
	}
	
}
