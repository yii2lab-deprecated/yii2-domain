<?php

namespace yii2lab\domain\base;

use Yii;
use yii\base\Action as YiiAction;
use yii2lab\domain\data\Query;
use yii2lab\domain\services\BaseService;
use yii2lab\domain\traits\action\ServiceTrait;

class Action extends YiiAction {
	
	use ServiceTrait;
	
	public $formClass;
	public $titleName;
	public $baseUrl;
	public $serviceMethod;
	public $serviceMethodParams;
	public $service;
	public $render;
	public $view;
	/**
	 * @var Query
	 */
	public $query = null;
	
	public function init()
	{
		parent::init();
		$this->initControllerProp('formClass');
		$this->initControllerProp('titleName');
		$this->initControllerProp('service');
		$this->initControllerProp('view');
		$this->baseUrl = $this->controller->getBaseUrl();
	}
	
	protected function createForm($defaultValue = null) {
		/** @var Model $model */
		$class = $this->formClass;
		$model = new $class;
		$formId = basename($class);
		if(Yii::$app->request->isPost) {
			$body = Yii::$app->request->post($formId);
			$model->setAttributes($body, false);
			$model->validate();
		} elseif(!empty($defaultValue)) {
			$model->setAttributes($defaultValue, false);
		}
		return $model;
	}
	
	protected function initControllerProp($name) {
		if(empty($this->{$name})) {
			$this->{$name} = $this->controller->{$name};
		}
	}
	
	public function redirect($url, $statusCode = 302)
	{
		return $this->controller->redirect($url, $statusCode);
	}
	
	public function render($view, $params = [])
	{
		return $this->controller->render($view, $params);
	}
	
}