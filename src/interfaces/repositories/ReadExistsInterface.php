<?php

namespace yii2lab\domain\interfaces\repositories;

use yii2lab\domain\data\Query;

interface ReadExistsInterface extends RepositoryInterface {
	
	public function isExists(Query $query);
	public function isExistsById($id);
	
}