<?php

namespace yii2lab\domain\helpers\repository;

use Yii;
use yii2lab\extension\arrayTools\helpers\Collection;
use yii2lab\domain\data\EntityCollection;
use yii2lab\domain\data\Query;
use yii2lab\domain\dto\WithDto;
use yii2lab\domain\exceptions\BadQueryHttpException;
use yii2lab\domain\helpers\DomainHelper;

class RelationHelper {
	
	public static function load($domain, $id, $query, $data, WithDto $ww = null) {
		$relations = RelationConfigHelper::getRelationsConfig($domain, $id);
		$withParams = RelationWithHelper::fetch($query, $remainOfWith);
		foreach($withParams as $relationName) {
			if(!array_key_exists($relationName, $relations)) {
				throw new BadQueryHttpException(Yii::t('domain/db', 'relation_not_defined {field}', ['field' => $relationName]));
			}
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
			
			if($query instanceof Query && $query->getNestedQuery($w->passed) instanceof Query) {
				$w->query = $query->getNestedQuery($w->passed);
				$w->query->with($w->remain[$w->relationName]);
			} else {
				$w->query = clone $query;
				$w->query->removeParam('with');
				$w->query->with($w->remain[$w->relationName]);
			}
			
			/*if(strpos($w->passed, DOT) !== false) {
				if($query instanceof Query && $query->getNestedQuery($w->passed) instanceof Query) {
					print_r($query->getNestedQuery($w->passed)->toArray());exit;
				}
				
			}*/
			
			$data = self::loadRelations($data, $w);
		}
		return $data;
	}
	
	private static function loadRelations($data, WithDto $w) {
		$isEntity = DomainHelper::isEntity($data);
		$collection = $isEntity ? [$data] : $data;
		$collection = self::loadRelationsForCollection($collection, $w);
		return $isEntity ? $collection[0] : $collection;
	}
	
	private static function loadRelationsForCollection($collection, WithDto $w) : array {
		if($collection instanceof Collection) {
			$collection = $collection->all();
		}
		/** @var EntityCollection $relCollection */
		$relCollection = JoinHelper::all($collection, $w->relationConfig);
		if(!empty($relCollection)) {
			foreach($collection as &$entity) {
				$relationEntity = RelationLoaderHelper::loadRelationItem($entity, $w, $relCollection);
				if(!empty($w->remain[$w->relationName])) {
					self::load($relationEntity->foreign->domain, $relationEntity->foreign->name, $w->query, $entity->{$w->relationName}, $w);
				}
			}
		}
		return $collection;
	}
	
}
