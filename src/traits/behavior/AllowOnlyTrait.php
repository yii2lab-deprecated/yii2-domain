<?php

namespace yii2lab\domain\traits\behavior;

trait AllowOnlyTrait {
	
	public $allowOnly = [];
	
	protected function isAllow() {
		$isAllow = \App::$domain->rbac->manager->isAllow($this->allowOnly);
		return $isAllow;
	}
	
}