<?php

namespace yii2lab\domain\enums;

use yii2lab\misc\enums\BaseEnum;

class RelationEnum extends BaseEnum {
	
	const ONE = 'one';
	const MANY = 'many';
	const MANY_TO_MANY = 'many-to-many';
	
}
