<?php

namespace yii2lab\domain\web\actions;

use common\exceptions\UnprocessableEntityHttpException;
use common\widgets\Alert;
use Yii;
use yii2lab\domain\base\Action;

class UpdateAction extends Action {
	
	public $serviceMethod = 'updateById';
	public $serviceMethodOne = 'oneById';
	
	public function run($id) {
		$this->view->title = t('main', 'update_title');
		$methodOne = $this->serviceMethodOne;
		$entity = $this->service->$methodOne($id);
		$model = $this->createForm($entity->toArray());
		if(Yii::$app->request->isPost && !$model->hasErrors()) {
			try{
				$method = $this->serviceMethod;
				$this->service->$method($id, $model->toArray());
				Yii::$app->notify->flash->send(['main', 'update_success'], Alert::TYPE_SUCCESS);
				return $this->redirect(['/' . $this->baseUrl . 'view', 'id' => $id]);
			} catch (UnprocessableEntityHttpException $e){
				$model->addErrorsFromException($e);
			}
		}
		return $this->render($this->render, compact('model'));
	}
}
