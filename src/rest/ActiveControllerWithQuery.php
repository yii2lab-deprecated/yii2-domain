<?php

namespace common\ddd\rest;

use Yii;

class ActiveControllerWithQuery extends Controller {
	
	public $usePagination = true;
	
	public function actions() {
		return [
			'index' => [
				'class' => 'common\ddd\rest\IndexActionWithQuery',
				'serviceMethod' => !empty($this->usePagination) ? 'getDataProvider' : 'findAll',
			],
			'create' => [
				'class' => 'common\ddd\rest\CreateAction',
			],
			'view' => [
				'class' => 'common\ddd\rest\ViewActionWithQuery',
			],
			'update' => [
				'class' => 'common\ddd\rest\UpdateAction',
				'serviceMethod' => 'updateById',
			],
			'delete' => [
				'class' => 'common\ddd\rest\DeleteAction',
				'serviceMethod' => 'deleteById',
			],
		];
	}
	
	protected function verbs() {
		return [
			'index' => ['GET', 'HEAD'],
			'view' => ['GET', 'HEAD'],
			'create' => ['POST'],
			'update' => ['PUT', 'PATCH'],
			'delete' => ['DELETE'],
			'options' => ['OPTIONS'],
		];
	}
	
	public function actionOptions() {
		if(Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
			Yii::$app->getResponse()->setStatusCode(405);
		}
		//Yii::$app->getResponse()->getHeaders()->set('Allow',['DELETE']);
	}
}
