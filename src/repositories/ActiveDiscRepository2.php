<?php

namespace common\ddd\repositories;

use common\ddd\interfaces\repositories\ModifyInterface;
use common\ddd\interfaces\repositories\ReadInterface;
use common\ddd\traits\ArrayModifyTrait;
use common\ddd\traits\ArrayReadTrait;
use common\ddd\traits\RelationTrait;

abstract class ActiveDiscRepository2 extends DiscRepository2 implements ReadInterface, ModifyInterface {

	use ArrayReadTrait;
	use ArrayModifyTrait;
	//use RelationTrait;

}