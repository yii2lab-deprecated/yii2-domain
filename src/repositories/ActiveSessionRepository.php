<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\traits\ArrayModifyTrait;
use yii2lab\domain\traits\ArrayReadTrait;

class ActiveSessionRepository extends SessionRepository {
	
	use ArrayReadTrait;
	use ArrayModifyTrait;
	
}