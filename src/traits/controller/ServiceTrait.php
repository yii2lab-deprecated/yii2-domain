<?php
namespace yii2lab\domain\traits\controller;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

trait ServiceTrait {
	
	public $serviceName = null;
	/** @var \yii2lab\domain\services\ActiveBaseService $model */
	public $service;
	
	protected function initService() {
		if($this->serviceName === null) {
			throw new InvalidConfigException('The "serviceName" property must be set.');
		}
		$this->service = ArrayHelper::getValue(Yii::$app, $this->serviceName);
	}
	
}