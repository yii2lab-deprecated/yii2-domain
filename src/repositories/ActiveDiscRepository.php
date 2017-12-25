<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\interfaces\repositories\ModifyInterface;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii2lab\domain\traits\ArrayModifyTrait;
use yii2lab\domain\traits\ArrayReadTrait;
use yii2lab\domain\traits\RelationTrait;

class ActiveDiscRepository extends DiscRepository2 implements ReadInterface, ModifyInterface {

	use ArrayReadTrait;
	use ArrayModifyTrait;
	//use RelationTrait;
	
	public function relations() {
		return [];
	}
}