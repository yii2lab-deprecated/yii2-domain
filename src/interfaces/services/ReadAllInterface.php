<?php

namespace yii2lab\domain\interfaces\services;

use yii2lab\domain\data\Query;

interface ReadAllInterface {
	
	/**
	 * @param Query|null $query
	 *
	 * @return array|null
	 */
	public function all(Query $query = null);
	
	/**
	 * @param Query|null $query
	 *
	 * @return integer
	 */
	public function count(Query $query = null);
	
}