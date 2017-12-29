<?php

namespace yii2lab\domain\rest;

use yii2lab\domain\data\GetParams;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii2mod\helpers\ArrayHelper;

class BaseAction extends Action {

	public $service;
	public $serviceMethod;
	public $serviceMethodParams = [];
	public $successStatusCode = 200;

	protected function responseToArray($response) {
		$response = !empty($response) ? $response : [];
		return $response;
	}

	protected function getQuery() {
		$params = Yii::$app->request->get();
		$getParams = new GetParams();
		return $getParams->getAllParams($params);
	}

	protected function getService() {
		if(empty($this->service)) {
			$this->service = $this->controller->service;
		}
		return $this->service;
	}

	protected function runServiceMethod() {
		$args = func_get_args();
		$params = $this->getParams($args);
		try {
			$response = call_user_func_array([$this->getService(), $this->serviceMethod], $params);
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
			$response = $e->getErrors();
			return $response;
		}
		$this->successStatusCode();
		if($this->successStatusCode != 200) {
			$response = null;
		}
		return $response;
	}

	protected function getParams($args) {
		if(empty($this->serviceMethodParams)) {
			return $args;
		}
		if(!is_array($this->serviceMethodParams)) {
			throw new InvalidConfigException('The "serviceMethodParams" property should be array.');
		}
		$firstArg = $args[0];
		$params = [];
		foreach($this->serviceMethodParams as $paramName) {
			$params[] = ArrayHelper::getValue($firstArg, $paramName);
		}
		return $params;
	}

	protected function successStatusCode() {
		Yii::$app->response->setStatusCode($this->successStatusCode);
	}
}
