<?php

namespace yii2lab\domain\web;

use Yii;
use yii2lab\extension\web\helpers\Behavior;

class ActiveController extends Controller {

	const RENDER_UPDATE = '@yii2lab/domain/views/active/update';
	const RENDER_CREATE = '@yii2lab/domain/views/active/create';
	const RENDER_INDEX = '@yii2lab/domain/views/active/index';
	const RENDER_VIEW = '@yii2lab/domain/views/active/view';

	const ACTION_UPDATE = 'yii2lab\domain\web\actions\UpdateAction';
	const ACTION_CREATE = 'yii2lab\domain\web\actions\CreateAction';
	const ACTION_INDEX = 'yii2lab\domain\web\actions\IndexAction';
	const ACTION_VIEW = 'yii2lab\domain\web\actions\ViewAction';
	const ACTION_DELETE = 'yii2lab\domain\web\actions\DeleteAction';

	public $formClass;
	public $baseUrl;
	public $titleName = 'title';

	public function getBaseUrl() {
		if(empty($this->baseUrl)) {
			$baseUrl = '/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id;
			$baseUrl = rtrim($baseUrl, '/') . '/';
			$this->baseUrl = $baseUrl;
		}
		return $this->baseUrl;
	}
	
	public function behaviors() {
		$behaviors = [];
		foreach($this->service->access() as $access) {
			$behaviors[] = Behavior::access($access['roles'], $access['only']);
		}
		return $behaviors;
	}
	
	public function actions() {
		return [
			'update' => [
				'class' => self::ACTION_UPDATE,
				'render' => self::RENDER_UPDATE,
			],
			'create' => [
				'class' => self::ACTION_CREATE,
				'render' => self::RENDER_CREATE,
			],
			'index' => [
				'class' => self::ACTION_INDEX,
				'render' => self::RENDER_INDEX,
			],
			'view' => [
				'class' => self::ACTION_VIEW,
				'render' => self::RENDER_VIEW,
			],
			'delete' => [
				'class' => self::ACTION_DELETE,
			],
		];
	}
	
}
