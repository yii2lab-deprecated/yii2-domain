<?php

namespace yii2lab\domain\interfaces\repositories;

use yii2lab\domain\data\Query;

interface SearchInterface extends RepositoryInterface {
	
	public function searchByText($text, Query $query = null);
	public function searchByTextFields();
	
}