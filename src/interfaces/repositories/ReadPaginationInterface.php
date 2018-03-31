<?php

namespace yii2lab\domain\interfaces\repositories;

use yii2lab\domain\data\Query;

interface ReadPaginationInterface extends RepositoryInterface {
	
	public function getDataProvider(Query $query = null);
	
}