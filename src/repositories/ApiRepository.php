<?php

namespace common\ddd\repositories;

use common\exceptions\UnprocessableEntityHttpException;
use common\helpers\Registry;
use Yii;
use yii\httpclient\Client;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class ApiRepository extends BaseRepository {
	
	/*const METHOD_POST = 'POST';
	const METHOD_GET = 'GET';
	const METHOD_PUT = 'PUT';
	const METHOD_DELETE = 'DELETE';
	
	public function sendGet($uri, $data = [], $headers = []) {
		$response = $this->sendRequest($uri, self::METHOD_GET, $data, $headers);
		return $response;
	}
	
	public function sendPost($uri, $data = [], $headers = []) {
		$response = $this->sendRequest($uri, self::METHOD_POST, $data, $headers);
		return $response;
	}
	
	public function sendPUT($uri, $data = [], $headers = []) {
		$response = $this->sendRequest($uri, self::METHOD_PUT, $data, $headers);
		return $response;
	}
	
	public function sendDelete($uri, $data = [], $headers = []) {
		$response = $this->sendRequest($uri, self::METHOD_DELETE, $data, $headers);
		return $response;
	}

	protected function sendRequest($uri, $method, $data = [], $headers = []) {
		$headers = $this->getHeaders($headers);
		$httpClient = new Client();
		$httpClient->baseUrl = env('servers.core.domain') . 'v4';
		$request = $httpClient->createRequest();
		$request
			->setMethod($method)
			->setUrl($uri)
			->setData($data)
			->setHeaders($headers);
		$response = $request->send();
		if($response->statusCode >= 400) {
			$this->showException($response);
		}
		return $response;
	}
	
	protected function showException($response) {
		$statusCode = $response->statusCode;
		if($statusCode == 422) {
			throw new UnprocessableEntityHttpException($response->data);
		} else {
			if($response->data['type']) {
				$exception = $response->data['type'];
				$message = $response->data['message'];
				throw new $exception($message);
			} else {
				if($statusCode == 401) {
					throw new UnauthorizedHttpException();
				} elseif($statusCode == 403) {
					throw new ForbiddenHttpException();
				} elseif($statusCode == 422) {
					throw new UnprocessableEntityHttpException($response->data);
				} elseif($statusCode == 404) {
					throw new NotFoundHttpException();
				} else {
					throw new ServerErrorHttpException();
				}
			}
		}
	}

	protected function getAuthorization() {
		$authorization = Yii::$app->request->headers->get('Authorization');
		if(!empty($authorization)) {
			return $authorization;
		}
		$authorization = Registry::get('secretKey');
		if(!empty($authorization)) {
			return $authorization;
		}
		return null;
	}

	protected function getLanguage() {
		$language = Yii::$app->request->headers->get('Language');
		if(!empty($language)) {
			return $language;
		}
		return null;
	}

	protected function getHeaders($headers = []) {
		if(empty($headers['Authorization'])) {
			$authorization = $this->getAuthorization();
			if(!empty($authorization)) {
				$headers['Authorization'] = $authorization;
			}
		}
		if(empty($headers['Language'])) {
			$language = $this->getLanguage();
			if(!empty($language)) {
				$headers['Language'] = $language;
			}
		}
		return $headers;
	}*/
	
}