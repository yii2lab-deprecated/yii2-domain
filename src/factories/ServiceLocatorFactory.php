<?php

namespace common\ddd\factories;

use common\ddd\locators\Base;
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
