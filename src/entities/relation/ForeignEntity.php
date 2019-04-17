<?php

namespace yii2lab\domain\entities\relation;

/**
 * Class ForeignEntity
 *
 * @package yii2lab\domain\entities\relation
 *
 * @property $field
 * @property $value
 * @property $query
 */
class ForeignEntity extends BaseForeignEntity {
	
	protected $field = 'id';
	protected $value;
	protected $query;
	
}