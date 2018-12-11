<?php

namespace yii2lab\domain\behaviors\query;

use yii2lab\domain\data\Query;
use yii2lab\extension\common\helpers\TypeHelper;

class PerPageLimitFilter extends BaseQueryFilter {
	
	public $perPage = 20;
	
	public function prepareQuery(Query $query) {
		$this->setLimit($query);
	}
	
	protected function setLimit(Query $query) {
		$perPage = $query->getParam('per-page', TypeHelper::INTEGER);
		if($perPage > $this->perPage) {
			$query->perPage($this->perPage);
		}
	}
}
