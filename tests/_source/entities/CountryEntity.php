<?php

namespace tests\_source\entities;

use yii2lab\domain\BaseEntity;

class CountryEntity extends BaseEntity {
	
	protected $id;
    protected $name;
    protected $currency;
    //protected $cities;
    //protected $regions;

	public function rules() {
		return [
			[['id', 'name'], 'required'],
			[['name'], 'trim'],
			[['name'], 'string', 'min' => 2],
			[['id'], 'integer'],
		];
	}
	
}