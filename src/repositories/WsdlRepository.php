<?php

namespace yii2lab\domain\repositories;

use api\v4\modules\payment\components\CoreLoginRequest;
use api\v4\modules\payment\components\UnsuccessfulResponseException;
use api\v4\modules\payment\components\WooppaySoapClient;
use yii2lab\domain\data\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\misc\exceptions\InvalidMethodParameterException;

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
	
	public function login($username = null) {
		if(empty($username)) {
			$username = $this->authLogin;
		}
		if($this->isLogged() && $username == $this->currentLogin) {
			return true;
		}
		$loginRequest = $this->createLoginRequest($username);
		try {
			$isLogin = $this->client->login($loginRequest);
		} catch(UnsuccessfulResponseException $e) {
			return false;
		}
		$this->currentLogin = $username;
		return $isLogin;
	}
	
	public function isLogged() {
		return !empty($this->currentLogin);
	}
	
	private function createLoginRequest($username) {
		$user = $this->oneUser($username);
		$loginRequest = new CoreLoginRequest();
		$loginRequest->username = $user['login'];
		$loginRequest->password = $user['password'];
		return $loginRequest;
	}
	
	private function oneUser($username) {
		if(empty($username)) {
			throw new InvalidMethodParameterException('Empty "username" parameter');
		}
		$userList = env('servers.wsdl.user');
		$query = Query::forge();
		$query->where('login', $username);
		return ArrayIterator::oneFromArray($query, $userList);
	}
}