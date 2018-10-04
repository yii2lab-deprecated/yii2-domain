<?php

namespace yii2lab\domain\factories;

use yii2lab\extension\common\helpers\ClassHelper;

abstract class BaseLocatorFactory extends BaseFactory {

	protected function genConfigs($components) {
		$configNew = [];
		foreach($components as $id => $config) {
			$configNew[$id] = $this->genConfig($id, $config);
		}
		return $configNew;
	}

	private function genConfig($id, $config) {
		$resultConfig = [];
		if(is_array($config)) {
			$resultConfig = $config;
		} else {
			if(ClassHelper::isClass($config)) {
				$resultConfig['class'] = $config;
			}
		}
		if(empty($resultConfig['class'])) {
			$resultConfig['class'] = $this->domain->path . BSL . $this->genClassName1($id, $config);
		}
		$resultConfig['id'] = $id;
		$resultConfig['domain'] = $this->domain;
		return $resultConfig;
	}

	abstract protected function genClassName1($id, $config);

}
