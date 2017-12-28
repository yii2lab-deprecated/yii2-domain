<?php

namespace yii2lab\domain\filters;

use Yii;
use yii\base\BaseObject;
use yii2lab\domain\Domain;
use yii2lab\helpers\Helper;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\misc\interfaces\FilterInterface;
use yii2module\lang\domain\helpers\LangHelper;

class NormalizeServices extends BaseObject implements FilterInterface {

	public function run($config) {
		$components = $config['components'];
		foreach($components as $name => $data) {
			$data = Helper::normalizeComponentConfig($data);
			if($this->isDomain($data)) {
				$domainInstance = Yii::createObject($data['class']);
				$domainConfig = $domainInstance->config();
				if(!empty($domainConfig)) {
					$data = ArrayHelper::merge($data, $domainConfig);
				}
				$config = $this->addTranslations($config, $data);
				$config['components'][$name] = $data;
			}
		}
		return $config;
	}
	
	private function addTranslations($config, $data) {
		if(empty($data['translations'])) {
			return $config;
		}
		foreach($data['translations'] as $id => $translationConfig) {
			$config['components']['i18n']['translations'][$id] = $translationConfig;
		}
		return $config;
	}
	
	private function isDomain($config) {
		if(empty($config['class'])) {
			return false;
		}
		if($config['class'] == Domain::class || is_subclass_of($config['class'], Domain::class)) {
			return true;
		}
		return false;
	}
	
}
