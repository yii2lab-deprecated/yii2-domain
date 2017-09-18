<?php

namespace yii2lab\domain\traits;

use yii2lab\domain\data\Query;
use Yii;
use yii\helpers\ArrayHelper;

trait RelationTrait {

	public function relations() {
		return [];
	}

	protected function attachRelations(Query $query, $collection) {
		if($query->hasParam('with')) {
			$with = $query->getParam('with');
			foreach($with as $rel) {
				$collection = $this->attachRelation($collection, $rel);
			}
		}
		return $collection;
	}

	private function attachRelation($collection, $rel) {
		$relations = $this->relations();
		if(empty($relations[$rel])) {
			return $collection;
		}
		$relation = $relations[$rel];
		$field = ArrayHelper::toArray($relation['field']);
		$value = [];
		foreach($field as $nn) {
			if($nn == '@query') {
				$value[] = new Query;
			} else {
				$value[] = $collection->$nn;
			}
		}
		$collection->$rel = $this->runRepositoryMethod($relation, $value);
		return $collection;
	}

	private function runRepositoryMethod($relation, $value) {
		$method = $relation['method'];
		$name = $relation['name'];
		$repository = $this->getRepositoryInstance($name);
		$result = call_user_func_array([$repository, $method], $value);
		return ArrayHelper::toArray($result);
	}

	private function getRepositoryInstance($name) {
		list($domain, $repo) = explode('.', $name);
		$repository = Yii::$app->$domain->repositories->$repo;
		return $repository;
	}
	
}