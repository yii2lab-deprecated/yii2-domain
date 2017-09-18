<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii2lab\domain\interfaces\repositories\ModifyInterface;
use yii2lab\domain\traits\ActiveRepositoryTrait;

class ActiveDiscRepository extends DiscRepository implements ReadInterface, ModifyInterface {
	
	use ActiveRepositoryTrait;
	
	public function count(Query $query = null) {
		$all = $this->all($query);
		return count($all);
	}
	
	public function insert(BaseEntity $entity) {
	
	}
	
	public function update(BaseEntity $entity) {
	
	}
	
	public function delete(BaseEntity $entity) {
	
	}
	
}