<?php

namespace common\ddd\web\actions;

use common\exceptions\UnprocessableEntityHttpException;
use common\widgets\Alert;
use Yii;
use common\ddd\base\Action;

class CreateAction extends Action {
	
	public $serviceMethod = 'create';
	
	public function run() {
		$this->view->title = t('main', 'create_title');
		$model =$this->createForm();
		if(Yii::$app->request->isPost && !$model->hasErrors()) {
			try{
				$method = $this->serviceMethod;
				$this->service->$method($model->toArray());
				Yii::$app->notify->flash->send(['main', 'create_success'], Alert::TYPE_SUCCESS);
				return $this->redirect($this->baseUrl);
			} catch (UnprocessableEntityHttpException $e){
				$model->addErrorsFromException($e);
			}
		}
		return $this->render($this->render, compact('model'));
	}
}
