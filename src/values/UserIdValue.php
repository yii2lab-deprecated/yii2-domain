<?php

namespace yii2lab\domain\values;

use Yii;
use yii2lab\domain\exceptions\ReadOnlyException;

class UserIdValue extends BaseValue {
	
	protected function _encode($value) {
		//return intval($value);
	}
	
	public function set($value) {
		throw new ReadOnlyException();
	}
	
	public function get($default = null) {
		return \App::$domain->account->auth->identity->id;
	}
	
	public function isValid($value) {
		return false;
	}
	
}
