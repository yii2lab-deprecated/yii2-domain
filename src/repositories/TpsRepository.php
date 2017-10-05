<?php

namespace yii2lab\domain\repositories;

use Yii;
use yii2woop\generated\exception\tps\NotAuthenticatedException;
use yii2woop\generated\request\BaseRequest;

class TpsRepository extends BaseRepository {
	
	public function send(BaseRequest $request) {
		try {
			return $request->send();
		} catch(NotAuthenticatedException $e) {
			Yii::$app->account->auth->breakSession();
		}
	}
	
}