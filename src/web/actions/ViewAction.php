<?php

namespace common\ddd\web\actions;

use common\ddd\base\Action;

class ViewAction extends Action {
	
	public $serviceMethod = 'oneById';
	
	public function run($id) {
		$method = $this->serviceMethod;
		$entity = $this->service->$method($id);
		$titleName = $this->titleName;
		if(!isset($entity->{$titleName})) {
			$this->view->title = $entity->{$titleName};
		}
		return $this->render($this->render, compact('entity'));
	}
}
