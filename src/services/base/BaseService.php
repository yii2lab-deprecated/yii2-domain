<?php

namespace yii2lab\domain\services\base;

use yii2lab\domain\data\Query;
use yii2lab\domain\Domain;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use Yii;
use yii\base\Component as YiiComponent;
use yii2lab\domain\repositories\BaseRepository;

/**
 * Class BaseService
 *
 * @package yii2lab\domain\services
 *
 * @property BaseRepository $repository
 * @property Domain $domain
 */
class BaseService extends YiiComponent {
	
	const EVENT_BEFORE_ACTION = 'beforeAction';
	const EVENT_AFTER_ACTION = 'afterAction';
	
	public $id;
	
	/** @var Domain */
	public $domain;
	
	public function access() {
		return [];
	}
	
	public function getRepository($name = null) {
		$name = !empty($name) ? $name : $this->id;
		return $this->domain->repositories->{$name};
	}
	
	// todo: move method in helper
	
	/**
	 * @param null $query
	 *
	 * @return null|Query
	 *
	 * @deprecated move to Query::forge()
	 */
	public function forgeQuery($query = null) {
		return Query::forge($query);
	}
	
	/**
	 * @param      $form
	 * @param null $data
	 * @param null $scenario
	 *
	 * @return array
	 * @throws UnprocessableEntityHttpException
	 * @throws \yii\base\InvalidConfigException
	 *
	 * @deprecated move to yii2lab\domain\helpers\Helper::validateForm
	 */
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