<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\UnknownPropertyException;
use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\services\BaseService;
use yii2mod\helpers\ArrayHelper;

class ServiceHelper {
	
	public static function has($serviceName) {
		try {
			$serviceInstance = ArrayHelper::getValue(Yii::$app, $serviceName);
			return is_object($serviceInstance);
		} catch(UnknownPropertyException $e) {
			return false;
		}
	}
	
	/**
	 * @param      $service
	 * @param mixed $default
	 *
	 * @return BaseService
	 * @throws InvalidConfigException
	 */
	public static function get($service, $default = null) {
		if($service instanceof BaseService) {
			return $service;
		}
		$serviceInstance = ArrayHelper::getValue(Yii::$app, $service, $default);
		if(!is_object($serviceInstance)) {
			throw new InvalidConfigException("Service \"$service\" not found");
		}
		return $serviceInstance;
	}
	
	/**
	 * @param $serviceName
	 * @param $id
	 *
	 * @return BaseEntity
	 * @throws InvalidConfigException
	 */
	public static function oneById($serviceName, $id)
	{
		if(!self::has($serviceName)) {
			return null;
		}
		$serviceInstance = self::get($serviceName);
		try {
			$result = $serviceInstance->oneById($id);
		} catch (NotFoundHttpException $e) {
			$result = null;
		}
		return $result;
	}
}