<?php

namespace common\ddd\web;

use Yii;

class ActiveController extends Controller {

	const RENDER_UPDATE = '@common/ddd/views/active/update';
	const RENDER_CREATE = '@common/ddd/views/active/create';
	const RENDER_INDEX = '@common/ddd/views/active/index';
	const RENDER_VIEW = '@common/ddd/views/active/view';

	const ACTION_UPDATE = 'common\ddd\web\actions\UpdateAction';
	const ACTION_CREATE = 'common\ddd\web\actions\CreateAction';
	const ACTION_INDEX = 'common\ddd\web\actions\IndexAction';
	const ACTION_VIEW = 'common\ddd\web\actions\ViewAction';
	const ACTION_DELETE = 'common\ddd\web\actions\DeleteAction';

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
		return $this->getAccessBehaviors();
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
