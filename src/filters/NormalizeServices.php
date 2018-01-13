<?php

namespace yii2lab\domain\filters;

use Yii;
use yii\base\BaseObject;
use yii2lab\domain\Domain;
use yii2lab\helpers\Helper;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\designPattern\filter\interfaces\FilterInterface;
use yii2module\lang\domain\helpers\DomainConfigHelper;

/**
 * Class NormalizeServices
 *
 * @package yii2lab\domain\filters
 *
 * @deprecated use LoadDomainConfig
 */
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
				$data['id'] = $name;
				$config['components'][$name] = $data;
			}
		}
		$config = DomainConfigHelper::addTranslations($config);
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
