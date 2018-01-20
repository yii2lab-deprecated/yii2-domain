<?php

namespace yii2lab\domain\filters;

use yii2lab\app\domain\filters\config\LoadConfig;
use yii2lab\designPattern\filter\interfaces\FilterInterface;
use yii2lab\domain\helpers\ConfigHelper;

class LoadDomainConfig extends LoadConfig implements FilterInterface {
	
	public $assignTo = 'components';
	
	protected function normalizeItem($domainId, $data) {
		return ConfigHelper::normalizeItemConfig($domainId, $data);
	}
	
}
