<?php

namespace yii2lab\domain\helpers\repository;

use yii\helpers\ArrayHelper;
use yii2lab\domain\entities\relation\RelationEntity;
use yii2lab\domain\enums\RelationEnum;
use yii2lab\domain\helpers\Helper;

class RelationConfigHelper {
	
	/**
	 * @param $domain
	 * @param $id
	 *
	 * @return RelationEntity[]
	 */
	public static function getRelationsConfig($domain, $id) {
		$repository = self::getRepositoryInstance($domain, $id);
		$relations =  $repository->relations();
		$relations = self::normalizeConfig($relations);
		return $relations;
	}
	
	private static function getRepositoryInstance($domain, $id) {
		$key = $domain . '.repositories.' . $id;
		$repository = ArrayHelper::getValue(\App::$domain, $key);
		return $repository;
	}
	
	private static function normalizeConfigItemForeign(RelationEntity $relation) {
		if(!empty($relation->foreign->id)) {
			$relation = self::prepare($relation, 'foreign');
		}
		return $relation;
	}
	
	private static function normalizeConfigItem(RelationEntity $relation) {
		if($relation->type == RelationEnum::MANY_TO_MANY && !empty($relation->via->id)) {
			$relation = self::prepare($relation, 'via');
		}
		if(!empty($relation->foreign)) {
			$relation = self::normalizeConfigItemForeign($relation);
		}
		return $relation;
	}
	
	private static function normalizeConfig(array $relations) {
		foreach($relations as &$relation) {
			/** @var RelationEntity $relation */
			if(!empty($relation['via']['this'])) {
				$relation['via']['self'] = $relation['via']['this'];
			}
			$relation = Helper::forgeEntity($relation, RelationEntity::class);
			//$relation->validate();
			$relation = self::normalizeConfigItem($relation);
		}
		/** @var RelationEntity[] $relations */
		return $relations;
	}
	
	private static function prepare(RelationEntity $relation, $key) {
		list($relation->{$key}->domain, $relation->{$key}->name) = explode('.', $relation->{$key}->id);
		$type = ArrayHelper::getValue($relation, 'type');
		$relation->type = RelationEnum::value($type);
		return $relation;
	}
	
}
