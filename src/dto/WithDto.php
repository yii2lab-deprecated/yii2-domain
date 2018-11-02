<?php

namespace yii2lab\domain\dto;

use yii2lab\domain\base\BaseDto;
use yii2lab\domain\data\Query;
use yii2lab\domain\entities\relation\RelationEntity;

class WithDto extends BaseDto {
	
	/**
	 * @var Query
	 */
	public $query;
	public $remain;
	public $remainOfRelation;
	public $relationName;
	
	/**
	 * @var RelationEntity
	 */
	public $relationConfig;
	public $passed;
	public $withParams;
	
}