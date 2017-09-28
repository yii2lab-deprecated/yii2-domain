<?php

namespace yii2lab\domain\entities;

use yii2lab\domain\BaseEntity;
use yii2lab\misc\enums\HttpMethod;

class RequestEntity extends BaseEntity {

	protected $method = HttpMethod::GET;
	protected $uri;
	protected $data = [];
	protected $headers = [];
	
	public function rules() {
		return [
			[['uri'], 'required'],
			[['method'], 'in', 'range' => HttpMethod::values()],
		];
	}

}