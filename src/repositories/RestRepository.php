<?php

namespace common\ddd\repositories;

use common\enums\app\ApiVersionEnum;
use Yii;
use yii\base\InvalidConfigException;

class RestRepository extends BaseRepository {
	
	public $version = ApiVersionEnum::VERSION_DEFAULT;
	public $baseUri = '';
	
	public function get($uri, $data = [], $headers = []) {
		$uri = $this->getUri($uri);
		$response = Yii::$app->core->client->get($uri, $data, $headers, $this->version);
		return $response;
	}
	
	public function post($uri, $data = [], $headers = []) {
		$uri = $this->getUri($uri);
		$response = Yii::$app->core->client->post($uri, $data, $headers, $this->version);
		return $response;
	}
	
	public function put($uri, $data = [], $headers = []) {
		$uri = $this->getUri($uri);
		$response = Yii::$app->core->client->put($uri, $data, $headers, $this->version);
		return $response;
	}
	
	public function del($uri, $data = [], $headers = []) {
		$uri = $this->getUri($uri);
		$response = Yii::$app->core->client->delete($uri, $data, $headers, $this->version);
		return $response;
	}
	
	private function getUri($uri) {
		if(empty($this->baseUri)) {
			throw new InvalidConfigException('Base URI not assigned!');
		}
		$isAbsolute = $uri{0} == '/';
		$baseUri = trim($this->baseUri, '/');
		$uri = trim($uri, '/');
		if(!empty($baseUri) && !$isAbsolute) {
			$uri = $baseUri . '/' . $uri;
		}
		$uri = trim($uri, '/');
		return $uri;
	}
	
}