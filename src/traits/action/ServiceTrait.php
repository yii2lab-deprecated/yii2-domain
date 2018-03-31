<?php
namespace yii2lab\domain\traits\action;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii2lab\domain\services\BaseService;

trait ServiceTrait {
	
	protected function runServiceMethod() {
		$args = func_get_args();
		$params = $this->getParams($args);
		$response = call_user_func_array([$this->getService(), $this->serviceMethod], $params);
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
	
	protected function getService() {
		if(!empty($this->service)) {
			if($this->service instanceof BaseService) {
				return $this->service;
			} elseif(is_string($this->service)) {
				return ArrayHelper::getValue(Yii::$domain, $this->service);
			} else {
				return $this->controller->service;
			}
		}
		
	}
	
}