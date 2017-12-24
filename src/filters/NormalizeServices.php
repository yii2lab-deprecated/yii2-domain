<?php

namespace yii2lab\domain\filters;

use Yii;
use yii\base\BaseObject;
use yii2lab\domain\Domain;
use yii2lab\helpers\Helper;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\misc\interfaces\FilterInterface;

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
				$config['components'][$name] = $data;
			}
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
