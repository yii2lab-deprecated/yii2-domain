<?php

namespace yii2lab\domain\repositories;

use yii\base\InvalidConfigException;
use yii2lab\rest\domain\helpers\RestHelper;

/**
 * Class CoreRepository
 *
 * @package yii2lab\domain\repositories
 * @deprecated use \yii2lab\core\domain\repositories\base\BaseCoreRepository
 */
class CoreRepository extends BaseRepository {
	
	public $version;
	public $baseUri = '';
	
	public function get($uri, $data = [], $headers = []) {
		$uri = $this->getUri($uri);
		$response = RestHelper::get($uri, $data, $headers);
		return $response;
	}
	
	public function post($uri, $data = [], $headers = []) {
		$uri = $this->getUri($uri);
		$response = RestHelper::post($uri, $data, $headers);
		return $response;
	}
	
	public function put($uri, $data = [], $headers = []) {
		$uri = $this->getUri($uri);
		$response = RestHelper::put($uri, $data, $headers);
		return $response;
	}
	
	public function del($uri, $data = [], $headers = []) {
		$uri = $this->getUri($uri);
		$response = RestHelper::del($uri, $data, $headers);
		return $response;
	}
	
	private function getUri($uri) {
		if(empty($this->baseUri)) {
			throw new InvalidConfigException('Base URI not assigned!');
		}
		if(empty($uri)) {
			$uri = '';
			$isAbsolute = false;
		} else {
			$isAbsolute = $uri{0} == SL;
		}
		$baseUri = trim($this->baseUri, SL);
		$uri = trim($uri, SL);
		if(!empty($baseUri) && !$isAbsolute) {
			$uri = $baseUri . SL . $uri;
		}
		$uri = trim($uri, SL);
		$domain = env('servers.core.domain');
		$domain = trim($domain, SL);
		return $domain . SL . $this->version . SL . $uri;
	}
	
}