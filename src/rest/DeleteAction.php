<?php

namespace yii2lab\domain\rest;

class DeleteAction extends BaseAction {

	public $serviceMethod = 'delete';
	public $successStatusCode = 204;
	
	public function run($id) {
		$this->runServiceMethod($id);
	}
}
