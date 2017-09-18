<?php

namespace common\ddd\rest;

use Yii;

class ActiveController extends Controller {
	
	public $usePagination = true;
	
	public function actions() {
		return [
			'index' => [
				'class' => 'common\ddd\rest\IndexAction',
				'serviceMethod' => !empty($this->usePagination) ? 'getDataProvider' : 'findAll',
			],
			'create' => [
				'class' => 'common\ddd\rest\CreateAction',
			],
			'view' => [
				'class' => 'common\ddd\rest\ViewAction',
			],
			'update' => [
				'class' => 'common\ddd\rest\UpdateAction',
			],
			'delete' => [
				'class' => 'common\ddd\rest\DeleteAction',
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
