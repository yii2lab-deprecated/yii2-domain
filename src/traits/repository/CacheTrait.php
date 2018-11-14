<?php

namespace yii2lab\domain\traits\repository;

use Closure;
use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\extension\common\helpers\PhpHelper;

trait CacheTrait {
	
	protected $cacheComponentName = 'cache';
	
	protected function cacheMethod($method, $params, $duration = null, Closure $isValidClosure = null) {
		$cacheKey = static::class . DOT . serialize($method) . DOT . serialize($params);
		$cacheComponent = $this->getCacheComponent();
		$data = $cacheComponent->get($cacheKey);
		if(empty($data)) {
			$data = $this->callMethod($method, $params);
			$isValidData = $this->isValidDataByClosure($data, $isValidClosure);
			if($isValidData) {
				$cacheComponent->set($cacheKey, $data, $duration);
			} else {
				$cacheComponent->delete($cacheKey);
			}
		}
		return $data;
	}
	
	private function callMethod($method, $params) {
		if(PhpHelper::isValidName($method)) {
			$targetMethod = ['parent', $method];
		} else {
			$targetMethod = $method;
		}
		$data = call_user_func_array($targetMethod, $params);
		return $data;
	}
	
	private function getCacheComponent($cacheComponentName = null) {
		$cacheComponentName = $cacheComponentName ?: $this->cacheComponentName;
		/** @var \yii\caching\CacheInterface $component */
		$component = ArrayHelper::getValue(Yii::$app, $cacheComponentName);
		return $component;
	}
	
	private function isValidDataByClosure($data, Closure $isValidClosure = null) {
		if($isValidClosure === null) {
			return true;
		}
		return (bool) $isValidClosure($data);
	}
	
}