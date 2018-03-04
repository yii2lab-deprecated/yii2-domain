<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii2mod\helpers\ArrayHelper;

class ServiceHelper {
	
	public static function has($serviceName) {
		$serviceInstance = ArrayHelper::getValue(Yii::$app, $serviceName);
		return is_object($serviceInstance);
	}
	
	public static function get($serviceName) {
		$serviceInstance = ArrayHelper::getValue(Yii::$app, $serviceName);
		if(!is_object($serviceInstance)) {
			throw new InvalidConfigException("Service \"$serviceName\" not found");
		}
		return $serviceInstance;
	}
	
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