<?php

namespace yii2lab\domain\helpers\repository;

use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii2lab\domain\data\Query;
use yii2lab\domain\entities\relation\BaseForeignEntity;
use yii2lab\domain\enums\RelationClassTypeEnum;
use yii2lab\domain\interfaces\services\ReadAllInterface;

class RelationRepositoryHelper {
	
	public static function getAll(BaseForeignEntity $relationConfig, Query $query = null) : array {
		$query = Query::forge($query);
		/** @var ReadAllInterface $repository */
		$repository = self::getInstance($relationConfig);
		return $repository->all($query);
	}
	
	private static function getInstance(BaseForeignEntity $relationConfigForeign) : BaseObject {
		$domainInstance = \App::$domain->get($relationConfigForeign->domain);
		if($relationConfigForeign->classType == RelationClassTypeEnum::SERVICE) {
			$locator = $domainInstance;
		} else {
			$locator = $domainInstance->repositories;
		}
		return ArrayHelper::getValue($locator, $relationConfigForeign->name);
	}
	
}
