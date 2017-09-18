<?php

namespace yii2lab\domain\interfaces\repositories;

use yii2lab\domain\data\Query;

interface ReadInterface {
	
	public function oneById($id, Query $query = null);
	
	//public function one(Query $query = null);
	public function all(Query $query = null);
	
	public function count(Query $query = null);
	
}