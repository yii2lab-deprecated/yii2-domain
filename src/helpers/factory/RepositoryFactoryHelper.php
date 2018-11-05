<?php

namespace yii2lab\domain\helpers\factory;

use yii\helpers\ArrayHelper;
use yii2lab\domain\Domain;

class RepositoryFactoryHelper extends BaseFactoryHelper {
	
	protected static function genClassName($id, $definition, Domain $domain) {
		/** @var string $class */
		$driver = self::getDriverFromConfig($definition, $domain);
		$class = 'repositories\\' . $driver . '\\' . ucfirst($id) . 'Repository';
		return $class;
	}
	
	private static function getDriverFromConfig($definition, Domain $domain) {
		if(!empty($definition)) {
			if(is_array($definition)) {
				$driver = ArrayHelper::getValue($definition, 'driver');
			} else {
				$driver = $definition;
			}
		}
		if(empty($driver)) {
			$driver = $domain->defaultDriver;
		}
		return $driver;
	}
	
}
