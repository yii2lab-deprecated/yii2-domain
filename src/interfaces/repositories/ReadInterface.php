<?php

namespace common\ddd\interfaces\repositories;

use common\ddd\data\Query;

interface ReadInterface {
	
	public function oneById($id, Query $query = null);
	
	//public function one(Query $query = null);
	public function all(Query $query = null);
	
	public function count(Query $query = null);
	
}