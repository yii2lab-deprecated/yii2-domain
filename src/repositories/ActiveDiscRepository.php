<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\interfaces\repositories\ModifyInterface;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii2lab\domain\traits\ArrayModifyTrait;
use yii2lab\domain\traits\ArrayReadTrait;

class ActiveDiscRepository extends DiscRepository implements ReadInterface, ModifyInterface {

	use ArrayReadTrait;
	use ArrayModifyTrait;
	
}