<?php

namespace yii2lab\domain\interfaces\repositories;

use yii2lab\domain\data\Query;

interface RepositoryInterface {
	
	public function getDataProvider(Query $query = null);
	public function scenarios();
	public function autoIncrementField();
	public function allFields();
	public function relations();
	public function uniqueFields();
	public function whereFields();
	public function sortFields();
	public function selectFields();
	public function fieldAlias();
	public function getAlias();
	public function getQueryValidator();
	public function forgeEntity($data, $class = null);
	
}
