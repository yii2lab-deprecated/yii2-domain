<?php

namespace yii2lab\domain\helpers\repository;

use yii2lab\domain\entities\relation\RelationEntity;
use yii2lab\domain\helpers\Helper;
use yii2lab\domain\repositories\BaseRepository;

class RelationConfigHelper {
	
	/**
	 * @param $domain
	 * @param $id
	 *
	 * @return RelationEntity[]
	 */
	public static function getRelationsConfig($domain, $id) : array {
		$repository = self::getRepositoryInstance($domain, $id);
		$relations =  $repository->relations();
		$relations = self::normalizeConfig($relations);
		$relations = Helper::forgeEntity($relations, RelationEntity::class, true, true);
		return $relations;
	}
	
	private static function getRepositoryInstance($domain, $id) : BaseRepository {
		$domainInstance = \App::$domain->get($domain);
		/** @var BaseRepository $repository */
		$repository = $domainInstance->repositories->get($id);
		return $repository;
	}
	
	private static function normalizeConfig(array $relations) : array {
		foreach($relations as &$relation) {
			/** @var RelationEntity $relation */
			if(!empty($relation['via']['this'])) {
				$relation['via']['self'] = $relation['via']['this'];
				unset($relation['via']['this']);
			}
		}
		return $relations;
	}
	
}
