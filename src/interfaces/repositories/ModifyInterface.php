<?php

namespace yii2lab\domain\interfaces\repositories;

use yii2lab\domain\BaseEntity;

interface ModifyInterface {
	
	public function insert(BaseEntity $entity);
	
	public function update(BaseEntity $entity);
	
	public function delete(BaseEntity $entity);
	
}