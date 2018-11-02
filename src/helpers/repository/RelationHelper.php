<?php

namespace yii2lab\domain\helpers\repository;

use Yii;
use yii2lab\domain\strategies\join\JoinStrategy;
use yii2lab\extension\arrayTools\helpers\Collection;
use yii2lab\domain\data\EntityCollection;
use yii2lab\domain\data\Query;
use yii2lab\domain\dto\WithDto;
use yii2lab\domain\exceptions\BadQueryHttpException;
use yii2lab\domain\helpers\DomainHelper;

class RelationHelper {
	
	public static function load(string $domain, string $id, Query $query, $data, WithDto $withDto = null) {
		$relations = RelationConfigHelper::getRelationsConfig($domain, $id);
		$remainOfWith = [];
		$withParams = RelationWithHelper::fetch($query, $remainOfWith);
		foreach($withParams as $relationName) {
			$newWithDto = self::forgeNewWithDto($relationName, $relations);
			$newWithDto->withParams = $withParams;
			$newWithDto->remain = $remainOfWith;
			self::hh($withDto, $newWithDto);
			self::prepareWithDto($query, $newWithDto);
			$data = self::loadRelations($data, $newWithDto);
		}
		return $data;
	}
	
	private static function hh($withDto, WithDto $newWithDto) : void {
		if($withDto instanceof WithDto) {
			$newWithDto->passed = trim($withDto->passed . DOT . $newWithDto->relationName, DOT);
		} else {
			$newWithDto->passed = $newWithDto->relationName;
		}
	}
	
	private static function forgeNewWithDto(string $relationName, array $relations) : WithDto {
		if(!array_key_exists($relationName, $relations)) {
			throw new BadQueryHttpException(Yii::t('domain/db', 'relation_not_defined {field}', ['field' => $relationName]));
		}
		$w = new WithDto();
		$w->relationConfig = $relations[$relationName];
		$w->relationName = $relationName;
		return $w;
		/*if(strpos($w->passed, DOT) !== false) {
			if($query instanceof Query && $query->getNestedQuery($w->passed) instanceof Query) {
				print_r($query->getNestedQuery($w->passed)->toArray());exit;
			}
		}*/
	}
	
	private static function prepareWithDto(Query $query, WithDto $withDto) : void {
		if($query->getNestedQuery($withDto->passed) instanceof Query) {
			$withDto->query = $query->getNestedQuery($withDto->passed);
			$withDto->query->with($withDto->remain[$withDto->relationName]);
		} else {
			$withDto->query = clone $query;
			$withDto->query->removeParam('with');
			$withDto->query->with($withDto->remain[$withDto->relationName]);
		}
	}
	
	private static function loadRelations($data, WithDto $w) {
		$isEntity = DomainHelper::isEntity($data);
		$collection = $isEntity ? [$data] : $data;
		$collection = self::loadRelationsForCollection($collection, $w);
		return $isEntity ? $collection[0] : $collection;
	}
	
	private static function loadRelationsForCollection($collection, WithDto $withDto) : array {
		if($collection instanceof Collection) {
			$collection = $collection->all();
		}
		/** @var EntityCollection $relCollection */
		
		$joinStrategy = new JoinStrategy();
		$joinStrategy->setStrategyName($withDto->relationConfig->type);
		$relCollection = $joinStrategy->join($collection, $withDto->relationConfig);
		
		if(!empty($relCollection)) {
			foreach($collection as &$entity) {
				$relationEntity = $joinStrategy->load($entity, $withDto, $relCollection);
				if(!empty($withDto->remain[$withDto->relationName])) {
					self::load($relationEntity->foreign->domain, $relationEntity->foreign->name, $withDto->query, $entity->{$withDto->relationName}, $withDto);
				}
			}
		}
		return $collection;
	}
	
}
