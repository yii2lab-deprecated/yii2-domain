<?php

namespace tests\_source\entities;

use paulzi\jsonBehavior\JsonBehavior;
use yii2lab\domain\behaviors\query\ReadOnlyBehavior;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\values\TimeValue;
use tests\_source\entities\CountryEntity;
use tests\_source\entities\RegionEntity;

/**
 * Class CityEntity
 *
 * @package tests\_source\entities
 *
 * @property $id
 * @property $type
 * @property $country_id
 * @property $region_id
 * @property $name
 * @property CountryEntity $country
 * @property RegionEntity $region
 * @property StreetEntity[] $streets
 * @property TimeValue|null $created_at
 * @property $hash
 */
class CityEntity extends BaseEntity {
	
	const TYPE_SMALL = 'small';
	const TYPE_BIG = 'big';
	
	protected $id;
	protected $type;
    protected $country_id;
    protected $region_id;
    protected $name;
    protected $country;
    protected $region;
	protected $streets;
	protected $created_at;
	protected $hash;

	public function rules() {
		$types = $this->getConstantEnum('type');
		return [
			[['country_id', 'country_id', 'region_id', 'name'], 'required'],
			[['name'], 'trim'],
			['name', 'string', 'min' => 2],
			[['id', 'country_id', 'region_id'], 'integer', 'min' => 1],
			[['type'], 'in', 'range' => $types],
		];
	}

   /* public function behaviors() {
        return [
            [
                'class' => ReadOnlyBehavior::class,
                'attributes' => ['id'],
            ],
        ];
    }*/

	public function fieldType() {
		return [
			'id' => 'integer',
			'country_id' => 'integer',
			'region_id' => 'integer',
			'name' => 'string',
			'country' => [
				'type' => CountryEntity::class,
			],
			'region' => [
				'type' => RegionEntity::class,
			],
			'streets' => [
				'type' => StreetEntity::class,
				'isCollection' => true,
			],
			'created_at' => TimeValue::class,
		];
	}

    public function readOnlyFields() {
        return [
            'id',
        ];
    }

	public function extraFields() {
		return [
			'hash',
		];
	}
	
	public function getHash() {
		return md5(serialize($this->toArray()));
	}
	
}