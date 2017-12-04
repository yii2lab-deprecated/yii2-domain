<?php

namespace yii2lab\domain\repositories;

use api\v4\modules\payment\components\CoreLoginRequest;
use api\v4\modules\payment\components\UnsuccessfulResponseException;
use api\v4\modules\payment\components\WooppaySoapClient;
use Yii;

class WsdlRepository extends BaseRepository {
	
	/** @var WooppaySoapClient */
	protected $client;
	protected $currentUser = false;
	
	public function init() {
		$this->client = new WooppaySoapClient();
	}
	
	public function login($username = null, $password = null) {
		if($this->isLogged() && $username == $this->currentUser) {
			return true;
		}
		$loginRequest = new CoreLoginRequest();
		if(!empty($username)) {
			$loginRequest->username = $username;
			$loginRequest->password = $password;
		} else {
			$loginRequest->username = Yii::$app->params['wooppay_api']['qr_merchant_login'];
			$loginRequest->password = Yii::$app->params['wooppay_api']['qr_merchant_password'];
		}
		try {
			$isLogin = $this->client->login($loginRequest);
		} catch(UnsuccessfulResponseException $e) {
			return false;
		}
		$this->currentUser = $username;
		return $isLogin;
	}
	
	public function isLogged() {
		return !empty($this->currentUser);
	}
	
}