<?php

namespace yii2lab\domain\web\actions;

use yii2lab\domain\base\Action;
use yii2lab\notify\domain\widgets\Alert;
use Yii;

class DeleteAction extends Action {
	
	public $serviceMethod = 'deleteById';
	
	public function run($id) {
		$method = $this->serviceMethod;
		$this->service->$method($id);
		Yii::$app->notify->flash->send(['main', 'delete_success'], Alert::TYPE_SUCCESS);
		return $this->redirect($this->baseUrl);
	}
}
