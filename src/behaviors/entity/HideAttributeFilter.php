<?php

namespace yii2lab\domain\behaviors\entity;

use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\events\ReadEvent;
use yii2lab\extension\yii\helpers\ArrayHelper;

class HideAttributeFilter extends BaseEntityFilter {
	
	public $secureAttributes = [];
	public $allowOnly = [];
	
	public function prepareContent(BaseEntity $entity, ReadEvent $event) {
		if(!$this->isAllow()) {
			$this->hideAttributes($entity);
		}
	}
	
	private function hideAttributes(BaseEntity $entity) {
		if(empty($this->secureAttributes)) {
			return;
		}
		foreach($this->secureAttributes as $attribute) {
			$entity->{$attribute} = null;
		}
	}
	
	private function isAllow() {
		if(empty($this->allowOnly)) {
			return false;
		}
		$allowOnlyPermissions = ArrayHelper::toArray($this->allowOnly);
		foreach($allowOnlyPermissions as $allowOnlyPermission) {
			try {
				$isAllow = \App::$domain->rbac->manager->checkAccess(\App::$domain->account->auth->identity->id, $allowOnlyPermission);
			} catch(ForbiddenHttpException $e) {
				$isAllow = false;
			} catch(UnauthorizedHttpException $e) {
				$isAllow = false;
			}
			if($isAllow) {
				return true;
			}
		}
		return false;
		/*try {
			\App::$domain->rbac->manager->can($this->allowOnly);
			return true;
		} catch(ForbiddenHttpException $e) {
			return false;
		} catch(UnauthorizedHttpException $e) {
			return false;
		}*/
	}
	
}
