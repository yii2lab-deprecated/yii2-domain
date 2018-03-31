<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\interfaces\repositories\CrudInterface;
use yii2lab\domain\traits\ArrayModifyTrait;
use yii2lab\domain\traits\ArrayReadTrait;

class ActiveDiscRepository extends DiscRepository implements CrudInterface {

	use ArrayReadTrait;
	use ArrayModifyTrait;
	
}