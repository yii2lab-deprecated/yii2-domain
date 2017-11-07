<?php

namespace yii2lab\domain\repositories;

use domain\v4\core\entities\RequestEntity;
use Yii;
use yii\httpclient\Client;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

class BaseRestRepository extends BaseRepository {
	
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
			Yii::$app->account->auth->breakSession();
			//throw new UnauthorizedHttpException();
		} elseif($statusCode == 403) {
			throw new ForbiddenHttpException();
		} elseif($statusCode == 422) {
			throw new UnprocessableEntityHttpException($response->data);
		} elseif($statusCode == 404) {
			throw new NotFoundHttpException(static::class);
		} else {
			if($response->data['type']) {
				$exception = $response->data['type'];
				$message = $response->data['message'];
				if($exception == 'yii2woop\\tps\\virt\\exceptions\\ExternalException') {
					if(YII_DEBUG) {
						throw new $exception($message);
					}
					throw new ServerErrorHttpException(t('tps', 'ExternalException'));
				}
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