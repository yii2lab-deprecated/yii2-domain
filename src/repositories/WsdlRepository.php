<?php

namespace yii2lab\domain\repositories;

// todo: перенести в domain/v4/transaction

use api\v4\modules\payment\components\CoreLoginRequest;
use api\v4\modules\payment\components\UnsuccessfulResponseException;
use api\v4\modules\payment\components\WooppaySoapClient;
use yii\web\ServerErrorHttpException;
use yii2lab\extension\console\helpers\Output;
use yii2lab\extension\arrayTools\helpers\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\extension\common\exceptions\InvalidMethodParameterException;

/**
 * Class WsdlRepository
 *
 * @package yii2lab\domain\repositories
 *
 * @deprecated перенести в проект
 */
class WsdlRepository extends BaseRepository {
	
	public $authLogin;
	
	/** @var WooppaySoapClient */
	protected $client;
	protected $currentLogin = false;
	
	public function init() {
		$this->client = new WooppaySoapClient();
		if(!empty($this->authLogin)) {
			$this->login($this->authLogin);
		}
	}
	
	public function login($username = null, $password = null) {
		if(empty($username)) {
			$username = $this->authLogin;
		}
		if($this->isLogged() && $username == $this->currentLogin) {
			return $this->client;
		}
		$loginRequest = $this->createLoginRequest($username, $password);
		try {
			$isLogin = $this->client->login($loginRequest);
		} catch(UnsuccessfulResponseException $e) {
			throw new ServerErrorHttpException('Invalid login or password in wsdl');
		}
		$this->currentLogin = $username;
		return $this->client;
	}
	
	public function isLogged() {
		return !empty($this->currentLogin);
	}
	
	private function createLoginRequest($username, $password = null) {
		$loginRequest = new CoreLoginRequest();
		$loginRequest->username = $username;
		if(!empty($password)) {
			$loginRequest->password = $password;
		} else {
			$user = $this->oneUser($username);
			$loginRequest->password = $user['password'];
		}
		return $loginRequest;
	}
	
	private function oneUser($username) {
		if(empty($username)) {
			throw new InvalidMethodParameterException('Empty "username" parameter');
		}
		$userList = env('servers.wsdl.user', []);
		if(empty($userList)) {
			throw new ServerErrorHttpException('List of users is not configured');
		}
		$query = Query::forge();
		$query->where('login', $username);
		return ArrayIterator::oneFromArray($query, $userList);
	}
}