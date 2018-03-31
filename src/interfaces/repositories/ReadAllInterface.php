<?php

namespace yii2lab\domain\interfaces\repositories;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;

interface ReadAllInterface extends RepositoryInterface {
	
	/**
	 * @param Query|null $query
	 *
	 * @return BaseEntity[]|null
	 */
	public function all(Query $query = null);
	
	/**
	 * @param Query|null $query
	 *
	 * @return integer
	 */
	public function count(Query $query = null);
	
}