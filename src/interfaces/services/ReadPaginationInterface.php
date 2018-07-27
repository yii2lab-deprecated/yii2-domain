<?php

namespace yii2lab\domain\interfaces\services;

use yii\data\DataProviderInterface;
use yii2lab\domain\data\Query;

interface ReadPaginationInterface {
	
	/**
	 * @param Query|null $query
	 *
	 * @return DataProviderInterface
	 */
	public function getDataProvider(Query $query = null);
	
}