<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\interfaces\repositories\CrudInterface;
use yii2lab\domain\traits\ArrayModifyTrait;
use yii2lab\domain\traits\ArrayReadTrait;

abstract class ActiveArrayRepository extends BaseRepository implements CrudInterface {
	
	use ArrayReadTrait;
	use ArrayModifyTrait;
	
	private $collection = [];
	
	protected function setCollection(Array $collection) {
		$this->collection = $collection;
	}
	
	protected function getCollection() {
		return $this->collection;
	}
}
