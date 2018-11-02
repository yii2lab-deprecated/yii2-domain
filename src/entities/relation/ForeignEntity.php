<?php

namespace yii2lab\domain\entities\relation;

/**
 * Class ForeignEntity
 *
 * @package yii2lab\domain\entities\relation
 *
 * @property $field
 * @property $value
 */
class ForeignEntity extends BaseForeignEntity {
	
	protected $field = 'id';
	protected $value;
	
}