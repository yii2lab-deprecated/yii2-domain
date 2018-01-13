<?php

namespace yii2lab\domain\filters;

use Yii;
use yii2lab\app\domain\filters\config\LoadConfig;
use yii2lab\designPattern\filter\interfaces\FilterInterface;
use yii2lab\helpers\Helper;
use yii2mod\helpers\ArrayHelper;

class LoadDomainConfig extends LoadConfig implements FilterInterface {
	
	public $assignTo = 'components';
	
	protected function normalizeItem($domainId, $data) {
		$data = Helper::normalizeComponentConfig($data);
		$data = Helper::isEnabledComponent($data);
		if(empty($data)) {
			return null;
		}
		$data = $this->normalizeSubItems($domainId, $data);
		if(!is_numeric($domainId)) {
			$data['id'] = $domainId;
		}
		if(!empty($data['class'])) {
			$domainInstance = Yii::createObject($data['class']);
			$domainConfig = $domainInstance->config();
			if(!empty($domainConfig)) {
				$domainConfig = $this->normalizeSubItems($domainId, $domainConfig);
				$data = ArrayHelper::merge($data, $domainConfig);
			}
		}
		return $data;
	}
	
	protected function normalizeSubItems($domainId, $config) {
		if(!empty($config['services'])) {
			$config['services'] = $this->genConfigs($config['services']);
		}
		if(!empty($config['repositories'])) {
			$config['repositories'] = $this->genConfigs($config['repositories']);
		}
		return $config;
	}
	
	protected function genConfigs($components) {
		$configNew = [];
		foreach($components as $id => $config) {
			if(is_integer($id)) {
				$id = $config;
				$config = [];
			}
			$configNew[$id] = $config;
		}
		return $configNew;
	}
	
}
