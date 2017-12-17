<?php

namespace yii2lab\domain\factories;

use yii2lab\domain\locators\Base;
use Yii;
use yii\helpers\ArrayHelper;

class RepositoryLocatorFactory extends BaseLocatorFactory {
	
	public function create($name, $params = []) {
		$locator = new Base;
		$locator->domain = $this->domain;
		$locator->components = $this->genConfigs($params);
		return $locator;
	}

	protected function genClassName1($id, $config) {
		/** @var string $class */
		$driver = $this->getDriverFromConfig($config);
		$class = 'repositories\\' . $driver . '\\' . ucfirst($id) . 'Repository';
		return $class;
	}

	private function getDriverFromConfig($config) {
		if(!empty($config)) {
			if(is_array($config)) {
				$driver = ArrayHelper::getValue($config, 'driver');
			} else {
				$driver = $config;
			}
		}
		if(empty($driver)) {
			$driver = $this->domain->getConfig('defaultDriver');
		}
		return $driver;
	}

}
