<?php

namespace yii2lab\domain\factories;

use yii2lab\domain\locators\Base;
use Yii;

class ServiceLocatorFactory extends BaseLocatorFactory {
	
	public function create($name, $params = []) {
		$locator = new Base;
		$locator->domain = $this->domain;
		$locator->components = $this->genConfigs($params);
		return $locator;
	}

	protected function genClassName1($id, $config) {
		$class = 'services\\';
		if(!empty($config) && is_string($config)) {
			$class .= $config . '\\';
		}
		$class .=  ucfirst($id) . 'Service';
		return $class;
	}

}
