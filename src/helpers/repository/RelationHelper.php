<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\dto\WithDto;
use yii2lab\helpers\DomainHelper;

class RelationHelper {
	
	public static function load($domain, $id, $query, $data, WithDto $ww = null) {
		$relations = RelationRepositoryHelper::getRelationsConfig($domain, $id);
		$withParams = RelationWithHelper::fetch($query, $remainOfWith);
		
		foreach($withParams as $relationName) {
			
			$w = new WithDto();
			
			$w->relationConfig = $relations[$relationName];
			$w->relationName = $relationName;
			$w->withParams = $withParams;
			$w->remain = $remainOfWith;
			if($ww instanceof WithDto) {
				$w->passed = trim($ww->passed . DOT . $relationName, DOT);
			} else {
				$w->passed = $relationName;
			}
			
			$w->query = clone $query;
			$w->query->removeParam('with');
			$w->query->with($w->remain[$w->relationName]);
			
			$data = self::loadRelations($data, $w);
		}
		return $data;
	}
	
	private static function loadRelations($data, WithDto $w) {
		$isEntity = DomainHelper::isEntity($data);
		$collection = $isEntity ? [$data] : $data;
		$relCollection = JoinHelper::all($collection, $w->relationConfig);
		foreach($collection as &$entity) {
			$foreignRelation = RelationLoaderHelper::loadRelationItem($entity, $w, $relCollection);
			if(!empty($w->remain[$w->relationName])) {
				self::load($foreignRelation['foreign']['domain'], $foreignRelation['foreign']['name'], $w->query, $entity->{$w->relationName}, $w);
			}
		}
		return $isEntity ? $collection[0] : $collection;
	}
	
}