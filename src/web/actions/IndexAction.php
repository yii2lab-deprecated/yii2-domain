<?php

namespace yii2lab\domain\web\actions;

use yii2lab\domain\base\Action;

class IndexAction extends Action {
	
	public $serviceMethod = 'getDataProvider';
	
	public function run() {
		$this->view->title = t('main', 'list_title');
		$method = $this->serviceMethod;
		$dataProvider = $this->service->$method();
		return $this->render($this->render, compact('dataProvider'));
	}
}
