<?php

namespace common\ddd\interfaces\services;

use common\ddd\data\Query;

interface ReadInterface {
	
	public function isExistsById($id);
	
	public function isExists($condition);
	
	public function one(Query $query = null);

	public function oneById($id, Query $query = null);
	
	public function all(Query $query = null);
	
	public function count(Query $query = null);
}