<?php

namespace yii2lab\domain\filters;

use yii2lab\app\domain\filters\config\LoadConfig;
use yii2lab\domain\helpers\ConfigHelper;

class LoadDomainConfig extends LoadConfig {
	
	public $assignTo = '';
	
	protected function normalizeItem($domainId, $data) {
		return ConfigHelper::normalizeItemConfig($domainId, $data);
	}
	
}
