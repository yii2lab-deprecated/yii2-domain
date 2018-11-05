<?php

namespace yii2lab\domain\helpers\factory;

use yii\helpers\ArrayHelper;

class FactoryHelper {
	
	public static function getDriverFromConfig($definition, $defaultDriver) {
		if(!empty($definition)) {
			if(is_array($definition)) {
				$driver = ArrayHelper::getValue($definition, 'driver');
			} else {
				$driver = $definition;
			}
		}
		if(empty($driver)) {
			$driver = $defaultDriver;
		}
		return $driver;
	}
	
}
