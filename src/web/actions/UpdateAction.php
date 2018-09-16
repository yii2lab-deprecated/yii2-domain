<?php

namespace yii2lab\domain\web\actions;

use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\navigation\domain\widgets\Alert;
use Yii;
use yii2lab\domain\base\Action;

class UpdateAction extends Action {
	
	public $serviceMethod = 'updateById';
	public $serviceMethodOne = 'oneById';
	
	public function run($id) {
		$this->view->title = Yii::t('main', 'update_title');
		$methodOne = $this->serviceMethodOne;
		$entity = $this->service->$methodOne($id);
		$model = $this->createForm($entity->toArray());
		if(Yii::$app->request->isPost && !$model->hasErrors()) {
			try{
				$method = $this->serviceMethod;
				$this->service->$method($id, $model->toArray());
				\App::$domain->navigation->alert->create(['main', 'update_success'], Alert::TYPE_SUCCESS);
				return $this->redirect(['/' . $this->baseUrl . 'view', 'id' => $id]);
			} catch (UnprocessableEntityHttpException $e){
				$model->addErrorsFromException($e);
			}
		}
		return $this->render($this->render, compact('model'));
	}
}
