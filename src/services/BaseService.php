<?php

namespace common\ddd\services;

use common\ddd\data\ActiveDataProvider;
use common\ddd\data\Query;
use common\ddd\Domain;
use common\ddd\interfaces\repositories\ReadInterface;
use common\exceptions\UnprocessableEntityHttpException;
use Yii;
use yii\base\Component as YiiComponent;

class BaseService extends YiiComponent {
	
	const EVENT_BEFORE_ACTION = 'beforeAction';
	const EVENT_AFTER_ACTION = 'afterAction';
	
	public $id;
	
	/** @var Domain */
	public $domain;
	
	public function access() {
		return [];
	}
	
	public function getDataProvider(Query $query = null) {
		$query = $this->forgeQuery($query);
		$isReadInterface = $this->repository instanceof ReadInterface;
		$isMethodExists = method_exists($this->repository, 'getDataProvider');
		if($isReadInterface && $isMethodExists) {
			$dataProvider = $this->repository->getDataProvider($query);
		} else {
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'service' => $this,
			]);
		}
		return $dataProvider;
	}
	
	public function getRepository($name = null) {
		$name = !empty($name) ? $name : $this->id;
		return $this->domain->repositories->{$name};
	}
	
	// todo: move method in helper
	
	public function forgeQuery($query) {
		return Query::forge($query);
	}
	
	protected function validateForm($form, $data = null, $scenario = null) {
		if(is_string($form) || is_array($form)) {
			$form = Yii::createObject($form);
		}
		/** @var \yii\base\Model $form */
		if(!empty($data)) {
			Yii::configure($form, $data);
		}
		if(!empty($scenario)) {
			$form->scenario = $scenario;
		}
		if(!$form->validate()) {
			throw new UnprocessableEntityHttpException($form);
		}
		return $form->getAttributes();
	}
	
}