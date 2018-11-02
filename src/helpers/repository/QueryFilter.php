<?php

namespace yii2lab\domain\helpers\repository;

use yii\base\BaseObject;
use yii2lab\domain\data\Query;
use yii2lab\domain\repositories\BaseRepository;

/**
 * Class QueryFilter
 *
 * @package yii2lab\domain\helpers\repository
 *
 * @property Query $query
 */
class QueryFilter extends BaseObject {
	
	/**
	 * @var BaseRepository
	 */
	public $repository;
	private $query;
	private $with;
	
	public function getQueryWithoutRelations() : Query {
		$query = clone $this->query;
		$this->with = RelationWithHelper::cleanWith($this->repository->relations(), $query);
		return $query;
	}
	
	public function loadRelations($data) {
		if(empty($this->with)) {
			return $data;
		}
		return RelationHelper::load($this->repository->domain->id, $this->repository->id, $this->query, $data);
	}
	
	public function getQuery() : Query {
		if(!isset($this->query)) {
			$this->query = Query::forge();
		}
		return $this->query;
	}
	
	public function setQuery(Query $query) {
		$this->query = clone $query;
	}
	
}
