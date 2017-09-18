<?php

namespace yii2lab\domain\rest;

class ViewActionWithQuery extends BaseAction {

	public $serviceMethod = 'oneById';
	
	public function run($id) {
		$query = $this->getQuery();
		return $this->runServiceMethod($id, $query);
	}

}
