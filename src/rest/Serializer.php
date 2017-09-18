<?php

namespace yii2lab\domain\rest;

use yii2lab\domain\helpers\TypeHelper;
use Yii;
use yii\rest\Serializer as YiiSerializer;

class Serializer extends YiiSerializer {
	
	public $format = [];
	
	protected function serializeModel($model) {
		$item = parent::serializeModel($model);
		if(!empty($item)) {
			$item = TypeHelper::serialize($item, $this->format);
		}
		return $item;
	}
	
	protected function serializeModels(array $models) {
		$models = parent::serializeModels($models);
		foreach($models as &$item) {
			$item = TypeHelper::serialize($item, $this->format);
		}
		return $models;
	}
}
