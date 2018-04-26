<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\interfaces\repositories\CrudInterface;
use yii2lab\domain\traits\ArrayModifyTrait;
use yii2lab\domain\traits\ArrayReadTrait;

/**
 * Class ActiveDiscRepository
 *
 * @package yii2lab\domain\repositories
 *
 * @deprecated use class \yii2lab\extension\filedb\repositories\base\BaseActiveFiledbRepository
 */
abstract class ActiveDiscRepository extends DiscRepository implements CrudInterface {

	use ArrayReadTrait;
	use ArrayModifyTrait;
	
}