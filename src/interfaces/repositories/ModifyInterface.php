<?php

namespace common\ddd\interfaces\repositories;

use common\ddd\BaseEntity;

interface ModifyInterface {
	
	public function insert(BaseEntity $entity);
	
	public function update(BaseEntity $entity);
	
	public function delete(BaseEntity $entity);
	
}