<?php

namespace yii2lab\domain\entities;

use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii2lab\domain\BaseEntity;

/**
 * Class ServiceExecutorEntity
 * @package yii2lab\domain\entities
 *
 * @property $id string
 * @property $service string
 * @property $domain string
 * @property $method string
 * @property $params array
 */
class ServiceExecutorEntity extends BaseEntity {

	protected $service;
	protected $domain;
	protected $method;
	protected $params = [];
	
	public function setId($value) {
		if(strpos($value, DOT) === false) {
			throw new InvalidArgumentException('Invalid id format!');
		}
		list($this->domain, $this->service) = explode(DOT, $value);
	}
	
	public function getId() {
		if(empty($this->name)) {
			throw new InvalidArgumentException('Service name can not be empty!');
		}
		if(empty($this->domain)) {
			throw new InvalidArgumentException('Domain name can not be empty!');
		}
		return $this->domain . DOT . $this->service;
	}
	
	/**
	 * @return array
	 */
	public function getParams(): array {
		return ArrayHelper::toArray($this->params);
	}
	
}