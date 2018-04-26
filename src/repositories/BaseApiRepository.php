<?php

namespace yii2lab\domain\repositories;

use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\rest\domain\entities\RequestEntity;
use yii\httpclient\Client;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

/**
 * Class BaseApiRepository
 *
 * @package yii2lab\domain\repositories
 *
 * @deprecated use class \\yii2lab\rest\domain\repositories\base\BaseRestRepository
 */
abstract class BaseApiRepository extends BaseRepository {
	
	abstract public function getBaseUrl();
	
	/**
	 * @param RequestEntity $requestEntity
	 *
	 * @return \yii\httpclient\Response
	 */
	public function send(RequestEntity $requestEntity) {
		$request = $this->createHttpRequest($requestEntity);
		try {
			$response = $request->send();
		
		} catch(\yii\httpclient\Exception $e) {
			throw new ServerErrorHttpException('Url "' . $request->url . '" is not available');
		}
		
		if($response->statusCode >= 400) {
			$this->showUserException($response);
		}
		if($response->statusCode >= 500) {
			$this->showServerException($response);
		}
		if($response->statusCode == 201 || $response->statusCode == 204) {
			$response->content = null;
		}
		return $response;
	}
	
	/**
	 * @param RequestEntity $requestEntity
	 *
	 * @return \yii\httpclient\Request
	 */
	protected function createHttpRequest(RequestEntity $requestEntity) {
		$requestEntity->validate();
		$httpClient = new Client();
		$httpClient->baseUrl = $this->getBaseUrl();
		$request = $httpClient->createRequest();
		$request
			->setOptions($requestEntity->options)
			->setMethod($requestEntity->method)
			->setUrl($requestEntity->uri)
			->setData($requestEntity->data)
			->setHeaders($requestEntity->headers)
			->addHeaders(['user-agent' => 'Awesome-Octocat-App']);
		return $request;
	}
	
	protected function showServerException($response) {
		throw new ServerErrorHttpException();
	}
	
	protected function showUserException($response) {
		$statusCode = $response->statusCode;
		if($statusCode == 401) {
			throw new UnauthorizedHttpException();
		} elseif($statusCode == 403) {
			throw new ForbiddenHttpException();
		} elseif($statusCode == 422) {
			throw new UnprocessableEntityHttpException();
		} elseif($statusCode == 404) {
			throw new NotFoundHttpException(__METHOD__ . ':' . __LINE__);
		}
	}
}