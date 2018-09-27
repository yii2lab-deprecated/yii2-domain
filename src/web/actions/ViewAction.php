<?php

namespace yii2lab\domain\web\actions;

use yii2lab\domain\base\Action;

class ViewAction extends Action {
	
	public $serviceMethod = 'oneById';
	
	public function run($id) {
		$method = $this->serviceMethod;
		$entity = $this->service->$method($id, $this->query);
		$titleName = $this->titleName;
		if(!isset($entity->{$titleName})) {
			$this->view->title = $entity->{$titleName};
		}
		return $this->render($this->render, compact('entity'));
	}
}
