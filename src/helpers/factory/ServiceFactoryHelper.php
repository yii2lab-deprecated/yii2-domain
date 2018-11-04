<?php

namespace yii2lab\domain\helpers\factory;

use yii\helpers\ArrayHelper;
use yii2lab\domain\Domain;
use yii2lab\domain\locators\Base;

class ServiceFactoryHelper extends BaseFactoryHelper {
	
	public static function create($name, $params = [], Domain $domain) {
		$locator = new Base;
		$locator->domain = $domain;
		$locator->components = self::genConfigs($params, $domain);
		return $locator;
	}
	
	protected static function genClassName1($id, $config, Domain $domain) {
		$class = 'services\\';
		if(!empty($config) && is_string($config)) {
			$class .= $config . '\\';
		}
		$class .=  ucfirst($id) . 'Service';
		return $class;
	}
	
}
