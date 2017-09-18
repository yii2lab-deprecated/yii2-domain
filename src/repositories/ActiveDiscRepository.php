<?php

namespace common\ddd\repositories;

use common\ddd\BaseEntity;
use common\ddd\data\Query;
use common\ddd\interfaces\repositories\ReadInterface;
use common\ddd\interfaces\repositories\ModifyInterface;
use common\ddd\traits\ActiveRepositoryTrait;

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