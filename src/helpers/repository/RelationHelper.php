<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\helpers\DomainHelper;

class RelationHelper {
	
	public static function load($domain, $id, $with, $data) {
		$relations = RelationRepositoryHelper::getRelationsConfig($domain, $id);
		$withParams = RelationWithHelper::fetch($with, $remainOfWith);
		foreach($withParams as $relationName) {
			$relationConfig = $relations[$relationName];
			$data = self::loadRelations($data, $relationConfig, $relationName, $remainOfWith);
		}
		return $data;
	}
	
	private static function loadRelations($data, $relationConfig, $relationName, $remainOfWith) {
		$isEntity = DomainHelper::isEntity($data);
		$collection = $isEntity ? [$data] : $data;
		$relCollection = JoinHelper::all($collection, $relationConfig);
		foreach($collection as &$entity) {
			$foreignRelation = RelationLoaderHelper::loadRelationItem($entity, $relationConfig, $relationName, $relCollection);
			if(!empty($remainOfWith[$relationName])) {
				self::load($foreignRelation['foreign']['domain'], $foreignRelation['foreign']['name'], $remainOfWith[$relationName], $entity->{$relationName});
			}
		}
		return $isEntity ? $collection[0] : $collection;
	}
	
}
