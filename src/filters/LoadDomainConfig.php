<?php

namespace yii2lab\domain\filters;

use Yii;
use yii2lab\app\domain\filters\config\LoadConfig;
use yii2lab\designPattern\filter\interfaces\FilterInterface;
use yii2lab\helpers\Helper;
use yii2mod\helpers\ArrayHelper;

class LoadDomainConfig extends LoadConfig implements FilterInterface {
	
	public function run($config) {
		$loadedConfig = self::requireConfigWithLocal($this->app, $this->name, $this->withLocal);
		foreach($loadedConfig as $name => $data) {
			$data = $this->normalizeItem($data);
			$data['id'] = $name;
			$loadedConfig[$name] = $data;
		}
		$config['components'] = \yii\helpers\ArrayHelper::merge($config['components'], $loadedConfig);
		return $config;
	}
	
	private function normalizeItem($data) {
		$data = Helper::normalizeComponentConfig($data);
		$domainInstance = Yii::createObject($data['class']);
		$domainConfig = $domainInstance->config();
		if(!empty($domainConfig)) {
			$data = ArrayHelper::merge($data, $domainConfig);
		}
		return $data;
	}
	
}
