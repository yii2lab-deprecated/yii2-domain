<?php
namespace yii2lab\domain\traits\controller;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

trait AccessTrait {
	
	protected function getAccessBehaviors($behaviors = []) {
		foreach($this->service->access() as $access) {
			list($permission, $only) = $access;
			$behaviors[] = $this->accessBase($permission, $only);
		}
		return $behaviors;
	}
	
	private function accessBase($permission, $only = null) {
		$permission = ArrayHelper::toArray($permission);
		$result['class'] = AccessControl::className();
		if(!empty($only)) {
			$result['only'] = $only;
		}
		$result['rules'] = [
			[
				'allow' => true,
				'roles' => $permission,
			],
		];
		return $result;
	}
	
}