<?php

namespace yii2lab\domain\dto;

use yii2lab\domain\base\BaseDto;

class WithDto extends BaseDto {

	public $query;
	public $remain;
	public $remainOfRelation;
	public $relationName;
	public $relationConfig;
	public $passed;
	public $withParams;
	
}