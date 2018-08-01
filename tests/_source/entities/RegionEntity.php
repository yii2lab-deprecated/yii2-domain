<?php

namespace tests\_source\entities;

use yii2lab\domain\BaseEntity;

class RegionEntity extends BaseEntity {
	
	protected $id;
    protected $country_id;
    protected $name;
    protected $country;
    protected $cities;

	public function rules() {
		return [
			[['name'], 'trim'],
			[['name', 'country_id'], 'required'],
			[['id', 'country_id'], 'integer'],
		];
	}
	
}