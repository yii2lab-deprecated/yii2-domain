<?php

namespace yii2lab\domain\rest;

class IndexActionWithQuery extends BaseAction {

	public $serviceMethod = 'getDataProvider';
	
	public function run() {
		$query = $this->getQuery();
		return $this->runServiceMethod($query);
	}

}
