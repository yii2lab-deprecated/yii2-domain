<?php

namespace yii2lab\domain\helpers\repository;

use yii\helpers\ArrayHelper;
use yii2lab\domain\data\Query;
use yii2lab\domain\enums\RelationEnum;
use yii2woop\service\domain\v3\enums\RelationClassTypeEnum;

class RelationRepositoryHelper {
	
	public static function getAll($relationConfig, Query $query = null) {
		$query = Query::forge($query);
		$relationConfig = self::normalizeConfigItemBase($relationConfig);
		$repository = RelationRepositoryHelper::getInstance($relationConfig);
		return $repository->all($query);
	}
	
	public static function getRelationsConfig($domain, $id) {
		$repository = RelationRepositoryHelper::getRepositoryInstance($domain, $id);
		$relations =  $repository->relations();
		$relations = self::normalizeConfig($relations);
		return $relations;
	}
	
	private static function getRepositoryInstance($domain, $id) {
		$key = $domain . '.repositories.' . $id;
		$repository = ArrayHelper::getValue(\App::$domain, $key);
		return $repository;
	}
	
	private static function getInstance($relationConfigForeign) {
		if($relationConfigForeign['classType'] == RelationClassTypeEnum::SERVICE) {
			$key = $relationConfigForeign['domain'] . '.' . $relationConfigForeign['name'];
		} else {
			$key = $relationConfigForeign['domain'] . '.repositories.' . $relationConfigForeign['name'];
		}
		$repository = ArrayHelper::getValue(\App::$domain, $key);
		return $repository;
	}
	
	private static function normalizeConfigItemBase($relation) {
		if(empty($relation['field'])) {
			$relation['field'] = 'id';
		}
		if(empty($relation['classType'])) {
			$relation['classType'] = RelationClassTypeEnum::REPOSITORY;
		}
		return $relation;
	}
	
	private static function normalizeConfigItemForeign($relation) {
		if(!empty($relation['foreign']['id'])) {
			$relation = self::prepare($relation, 'foreign');
		}
		$relation['foreign'] = self::normalizeConfigItemBase($relation['foreign']);
		return $relation;
	}
	
	private static function normalizeConfigItem(array $relation) {
		if($relation['type'] == RelationEnum::MANY_TO_MANY && !empty($relation['via']['id'])) {
			$relation = self::prepare($relation, 'via');
		}
		if(!empty($relation['foreign'])) {
			$relation = self::normalizeConfigItemForeign($relation);
		}
		return $relation;
	}
	
	private static function normalizeConfig(array $relations) {
		foreach($relations as &$relation) {
			$relation = self::normalizeConfigItem($relation);
		}
		return $relations;
	}
	
	private static function prepare(array $relation, $key) {
		list($relation[$key]['domain'], $relation[$key]['name']) = explode('.', $relation[$key]['id']);
		$type = ArrayHelper::getValue($relation, 'type');
		$relation['type'] = RelationEnum::value($type);
		return $relation;
	}
	
}
