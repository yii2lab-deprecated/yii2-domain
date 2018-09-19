<?php

namespace yii2lab\domain\behaviors\query;

use yii2lab\domain\data\Query;

class CurrentUserOnlyFilter extends BaseQueryFilter {
	
	public $attribute = 'user_id';
	
	public function prepareQuery(Query $query) {
		$query->removeWhere($this->attribute);
		$query->andWhere([$this->attribute => \App::$domain->account->auth->identity->id]);
	}
	
}
