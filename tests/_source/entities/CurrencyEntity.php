<?php

namespace tests\_source\entities;

use yii2lab\domain\BaseEntity;

class CurrencyEntity extends BaseEntity {

    protected $id;
    protected $country_id;
    protected $code;
    protected $name;
    protected $description;
    protected $country;

    public function rules() {
        return [
            [['id', 'name', 'code', 'country_id'], 'required'],
            [['name', 'code'], 'trim'],
            [['name', 'code'], 'string', 'min' => 2],
            [['id', 'country_id'], 'integer'],
        ];
    }
	
}