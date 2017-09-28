<?php

namespace yii2lab\domain\repositories;

use yii\base\InvalidConfigException;

class ApiRepository extends BaseApiRepository {
	
	public $baseUrl;
	
	public function getBaseUrl() {
		if(empty($this->baseUrl)) {
			throw new InvalidConfigException('Not setted baseUrl');
		}
		return $this->baseUrl;
	}
	
}