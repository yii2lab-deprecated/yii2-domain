<?php

namespace yii2lab\domain\entities\relation;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\enums\RelationClassTypeEnum;

/**
 * Class BaseForeignEntity
 *
 * @package yii2lab\domain\entities\relation
 *
 * @property $id
 * @property $domain
 * @property $name
 * @property $classType
 */
class BaseForeignEntity extends BaseEntity {
	
	protected $id;
	protected $domain;
	protected $name;
	protected $classType = RelationClassTypeEnum::REPOSITORY;
	
	public function rules() {
		return [
			[['classType'], 'in', 'range' => RelationClassTypeEnum::values()],
		];
	}
	
	public function setId($id) {
		list($this->domain, $this->name) = explode(DOT, $id);
	}
	
	public function getId() {
	
	}
	
}