<?php

namespace yii2lab\domain\behaviors\entity;

use yii\web\ForbiddenHttpException;
use yii2lab\domain\BaseEntity;

class HideAttributeFilter extends BaseEntityFilter {
	
	public $attributes = [];
	public $accessOnly = [];
	
	public function prepareContent(BaseEntity $entity) {
		if($this->isCan()) {
			return;
		}
		$this->hideAttributes($entity);
	}
	
	private function hideAttributes(BaseEntity $content) {
		if(empty($this->attributes)) {
			return;
		}
		foreach($this->attributes as $attribute) {
			$content->{$attribute} = null;
		}
	}
	
	private function isCan() {
		if(empty($this->accessOnly)) {
			return true;
		}
		try {
			\App::$domain->rbac->manager->can($this->accessOnly);
			return true;
		} catch(ForbiddenHttpException $e) {
			return false;
		}
	}
	
}
