<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ServiceHelper {
	
	public static function isHas($serviceName) {
		list($domain, $service) = explode('.', $serviceName);
		$services = config('components.' . $domain . '.services', []);
		if(!isset($services[$service]) && !in_array($service, $services)) {
			return false;
		}
		return true;
	}
	
	public static function oneById($serviceName, $id)
	{
		if(!self::isHas($serviceName)) {
			return null;
		}
		$serviceInstance = ArrayHelper::getValue(Yii::$app, $serviceName);
		if(!is_object($serviceInstance)) {
			throw new InvalidConfigException("Service \"$serviceName\" not found");
		}
		try {
			$result = $serviceInstance->oneById($id);
		} catch (NotFoundHttpException $e) {
			$result = null;
		}
		return $result;
	}
}