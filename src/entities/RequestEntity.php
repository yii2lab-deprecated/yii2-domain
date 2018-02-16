<?php

namespace yii2lab\domain\entities;

use yii2lab\domain\BaseEntity;
use yii2lab\misc\enums\HttpMethodEnum;

/**
 * Class RequestEntity
 * @package yii2lab\domain\entities
 *
 * @property $method string
 * @property $uri string
 * @property $data array
 * @property $headers array
 * @property $options array
 * @property-read $post array
 * @property-read $query array
 */
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

    public function getPost() {
        return $this->data;
    }

    public function getQuery() {
        return $this->data;
    }

}