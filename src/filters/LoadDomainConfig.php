<?php

namespace yii2lab\domain\filters;

use Yii;
use yii2lab\app\domain\filters\config\LoadConfig;
use yii2lab\designPattern\filter\interfaces\FilterInterface;
use yii2lab\helpers\Helper;
use yii2mod\helpers\ArrayHelper;

class LoadDomainConfig extends LoadConfig implements FilterInterface {
	
	public $assignTo = 'components';
	
	protected function normalizeItem($name, $data) {
		$data = Helper::normalizeComponentConfig($data);
		$data = Helper::isEnabledComponent($data);
		if(empty($data)) {
			return null;
		}
		$domainInstance = Yii::createObject($data['class']);
		$domainConfig = $domainInstance->config();
		if(!empty($domainConfig)) {
			$data = ArrayHelper::merge($data, $domainConfig);
		}
		if(!is_numeric($name)) {
			$data['id'] = $name;
		}
		return $data;
	}
	
}
