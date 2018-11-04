<?php

namespace yii2lab\domain\helpers\factory;

use yii\helpers\ArrayHelper;
use yii2lab\domain\Domain;
use yii2lab\domain\locators\Base;

class RepositoryFactoryHelper extends BaseFactoryHelper {
	
	public static function create($name, $params = [], Domain $domain) {
		$locator = new Base;
		$locator->domain = $domain;
		$locator->components = self::genConfigs($params, $domain);
		return $locator;
	}
	
	protected static function genClassName1($id, $config, Domain $domain) {
		/** @var string $class */
		$driver = self::getDriverFromConfig($config, $domain);
		$class = 'repositories\\' . $driver . '\\' . ucfirst($id) . 'Repository';
		return $class;
	}
	
	private static function getDriverFromConfig($config, Domain $domain) {
		if(!empty($config)) {
			if(is_array($config)) {
				$driver = ArrayHelper::getValue($config, 'driver');
			} else {
				$driver = $config;
			}
		}
		if(empty($driver)) {
			$driver = $domain->defaultDriver;
		}
		return $driver;
	}
	
}
