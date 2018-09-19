<?php

namespace yii2lab\domain\behaviors\entity;

use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\events\ReadEvent;

class HideAttributeFilter extends BaseEntityFilter {
	
	public $secureAttributes = [];
	public $allowOnly = [];
	
	public function prepareContent(BaseEntity $entity, ReadEvent $event) {
		if($this->isCan()) {
			return;
		}
		$this->hideAttributes($entity);
	}
	
	private function hideAttributes(BaseEntity $content) {
		if(empty($this->secureAttributes)) {
			return;
		}
		foreach($this->secureAttributes as $attribute) {
			$content->{$attribute} = null;
		}
	}
	
	private function isCan() {
		if(empty($this->allowOnly)) {
			return true;
		}
		try {
			\App::$domain->rbac->manager->can($this->allowOnly);
			return true;
		} catch(ForbiddenHttpException $e) {
			return false;
		} catch(UnauthorizedHttpException $e) {
			return false;
		}
	}
	
}
