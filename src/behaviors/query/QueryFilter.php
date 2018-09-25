<?php

namespace yii2lab\domain\behaviors\query;

use yii2lab\domain\data\Query;

/**
 * Class QueryFilter
 *
 * [
 * 'class' => QueryFilter::class,
 * 'method' => 'with',
 * 'value' => 'fields',
 * ],
 * [
 * 'class' => QueryFilter::class,
 * 'method' => 'addOrderBy',
 * 'value' => ['priority' => SORT_DESC],
 * ],
 *
 * @package yii2lab\domain\behaviors\query
 */
class QueryFilter extends BaseQueryFilter {
	
	public $method;
	public $params;
	
	public function prepareQuery(Query $query) {
		$method = $this->method;
		$query->$method($this->params);
	}
	
}
