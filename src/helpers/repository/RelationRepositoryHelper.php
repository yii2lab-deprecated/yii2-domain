<?php

namespace yii2lab\domain\helpers\repository;

use yii\helpers\ArrayHelper;
use yii2lab\domain\data\Query;
use yii2lab\domain\entities\relation\BaseForeignEntity;
use yii2lab\domain\enums\RelationClassTypeEnum;

class RelationRepositoryHelper {
	
	public static function getAll(BaseForeignEntity $relationConfig, Query $query = null) {
		$query = Query::forge($query);
		$repository = self::getInstance($relationConfig);
		return $repository->all($query);
	}
	
	private static function getInstance(BaseForeignEntity $relationConfigForeign) {
		if($relationConfigForeign->classType == RelationClassTypeEnum::SERVICE) {
			$key = $relationConfigForeign->domain . '.' . $relationConfigForeign->name;
		} else {
			$key = $relationConfigForeign->domain . '.repositories.' . $relationConfigForeign->name;
		}
		$repository = ArrayHelper::getValue(\App::$domain, $key);
		return $repository;
	}
	
}
