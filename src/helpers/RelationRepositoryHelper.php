<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\domain\enums\RelationEnum;

class RelationRepositoryHelper {
	
	public static function getOne($domain, $id, Query $query = null) {
		$query = Query::forge($query);
		$repository = RelationRepositoryHelper::getInstance($domain, $id);
		return $repository->one($query);
	}
	
	public static function getAll($domain, $id, Query $query = null) {
		$query = Query::forge($query);
		$repository = RelationRepositoryHelper::getInstance($domain, $id);
		return $repository->all($query);
	}
	
	public static function getRelationsConfig($domain, $id) {
		$repository = RelationRepositoryHelper::getInstance($domain, $id);
		$relations =  $repository->relations();
		$relations = self::normalizeConfig($relations);
		return $relations;
	}
	
	private static function getInstance($domain, $id) {
		$key = $domain . '.repositories.' . $id;
		$repository = ArrayHelper::getValue(Yii::$app, $key);
		return $repository;
	}
	
	private static function normalizeConfig($relations) {
		foreach($relations as &$relation) {
			if(!empty($relation['foreign']['id'])) {
				list($relation['foreign']['domain'], $relation['foreign']['name']) = explode('.', $relation['foreign']['id']);
				$type = ArrayHelper::getValue($relation, 'type');
				$relation['type'] = RelationEnum::value($type);
			}
			if(empty($relation['foreign']['field'])) {
				$relation['foreign']['field'] = 'id';
			}
		}
		return $relations;
	}
}
