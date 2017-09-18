<?php

namespace common\ddd\services;

use common\ddd\repositories\RestRepository;
use common\enums\app\ApiVersionEnum;
use yii\base\InvalidConfigException;

class RestBaseService extends BaseService {
	
	public $version = ApiVersionEnum::VERSION_DEFAULT;
	public $baseUri = '';
	private $client;
	
	public function getClient() {
		if(!is_object($this->client)) {
			if(empty($this->baseUri)) {
				throw new InvalidConfigException('Base URI not assigned!');
			}
			$this->client = new RestRepository;
			$this->client->baseUri = $this->baseUri;
			$this->client->version = $this->version;
		}
		return $this->client;
	}
	
}