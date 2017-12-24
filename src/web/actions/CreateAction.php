<?php

namespace yii2lab\domain\web\actions;

use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\notify\domain\widgets\Alert;
use Yii;
use yii2lab\domain\base\Action;

class CreateAction extends Action {
	
	public $serviceMethod = 'create';
	
	public function run() {
		$this->view->title = Yii::t('main', 'create_title');
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
