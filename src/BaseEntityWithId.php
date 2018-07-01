<?php

namespace yii2lab\domain;

/**
 * Class RestEntity
 *
 * @package yii2lab\rest\domain\entities
 *
 * @property $id
 */
class BaseEntityWithId extends BaseEntity {
	
	protected $id;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($value) {
		if(empty($this->id)) {
			$this->id = $value;
		}
	}
	
}