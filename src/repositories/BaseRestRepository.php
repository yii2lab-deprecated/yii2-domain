<?php

namespace yii2lab\domain\repositories;

use Yii;
use yii\httpclient\Client;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

/**
 * Class BaseRestRepository
 *
 * @package yii2lab\domain\repositories
 *
 * @deprecated use class \yii2lab\rest\domain\repositories\base\BaseRestRepository
 */
abstract class BaseRestRepository extends BaseRepository {
	
	public function send(RequestEntity $requestEntity) {
		$requestEntity->validate();
		//$headers = $this->getHeaders($requestEntity->headers);
		$headers = $requestEntity->headers;
		$request = $this->createRequest($requestEntity->version);
		$request
			->setMethod($requestEntity->method)
			->setUrl($requestEntity->uri)
			->setData($requestEntity->data)
			->setHeaders($headers);
		$response = $request->send();
		if($response->statusCode >= 400) {
			$this->showException($response);
		}
		if($response->statusCode == 201 || $response->statusCode == 204) {
			$response->content = null;
		}
		return $response;
	}
	
	protected function showException($response) {
		$statusCode = $response->statusCode;
		if($statusCode == 401) {
			\App::$domain->account->auth->breakSession();
			//throw new UnauthorizedHttpException();
		} elseif($statusCode == 403) {
			throw new ForbiddenHttpException();
		} elseif($statusCode == 422) {
			throw new UnprocessableEntityHttpException($response->data);
		} elseif($statusCode == 404) {
			throw new NotFoundHttpException(__METHOD__ . ':' . __LINE__);
		} else {
			if($response->data['type']) {
				$exception = $response->data['type'];
				$message = $response->data['message'];
				throw new $exception($message);
			} else {
				throw new ServerErrorHttpException();
			}
		}
	}
	
	protected function createRequest($version) {
		$httpClient = new Client();
		$baseUrl = env('servers.core.domain');
		if(YII_ENV_TEST) {
			$baseUrl .= 'index-test.php/';
		}
		$httpClient->baseUrl = $baseUrl . $version;
		$request = $httpClient->createRequest();
		return $request;
	}
	
}