<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2lab\domain\data\Query;
use yii2lab\domain\enums\RelationEnum;

class RelationRepositoryHelper {
	
	public static function getRelationNameByField($relations, $field) {
		foreach($relations as $relationName => $relation) {
			if($relation['field'] == $field) {
				return $relationName;
			}
		}
	}
	
	public static function getOne($domain, $id, Query $query = null) {
		$query = Query::forge($query);
		$repository = RelationRepositoryHelper::getInstance($domain, $id);
		try {
			return $repository->one($query);
		} catch(NotFoundHttpException $e) {
			return null;
		}
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
			
			if($relation['type'] == RelationEnum::MANY_TO_MANY && !empty($relation['via']['id'])) {
				$relation = self::prepare($relation, 'via');
			}
			
			if(!empty($relation['foreign']['id'])) {
				$relation = self::prepare($relation, 'foreign');
			}
			
			if(!empty($relation['this']['id'])) {
				$relation = self::prepare($relation, 'this');
			}
			
			if(empty($relation['foreign']['field'])) {
				$relation['foreign']['field'] = 'id';
			}
			
		}
		return $relations;
	}
	
	private static function prepare($relation, $key) {
		list($relation[$key]['domain'], $relation[$key]['name']) = explode('.', $relation[$key]['id']);
		$type = ArrayHelper::getValue($relation, 'type');
		$relation['type'] = RelationEnum::value($type);
		return $relation;
	}
}
