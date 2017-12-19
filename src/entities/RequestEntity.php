<?php

namespace yii2lab\domain\entities;

use yii2lab\domain\BaseEntity;
use yii2lab\misc\enums\HttpMethodEnum;

class RequestEntity extends BaseEntity {

	protected $method = HttpMethodEnum::GET;
	protected $uri;
	protected $data = [];
	protected $headers = [];
	protected $options = [];
	
	public function rules() {
		return [
			[['uri'], 'required'],
			[['method'], 'in', 'range' => HttpMethodEnum::values()],
		];
	}

}