<?php

namespace tests\_source\entities;

use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\values\ArrayValue;
use yii2lab\domain\values\TimeValue;

/**
 * Class PostEntity
 *
 * @package tests\_source\entities
 *
 * @property $id
 * @property $text
 * @property Collection $categories_id
 * @property $created_at
 * @property $updated_at
 * @property $created_by
 * @property $updated_by
 */
class PostEntity extends BaseEntity {
	
	protected $id;
	protected $text;
    protected $categories_id;
	protected $created_at;
	protected $updated_at;
	protected $created_by = null;
	protected $updated_by = null;
	
	public function behaviors() {
		return [
			[
				'class' => BlameableBehavior::class,
				'attributes' => [
					self::EVENT_INIT => ['created_by', 'updated_by'],
					self::EVENT_BEFORE_SET_ATTRIBUTE => ['updated_by'],
				],
			],
			[
				'class' => TimestampBehavior::class,
				'attributes' => [
					self::EVENT_INIT => ['created_at', 'updated_at'],
					self::EVENT_BEFORE_SET_ATTRIBUTE => ['updated_at'],
				],
				//'value' => new TimeValue(),
			],
		];
	}

    public function fieldType() {
        return [
            'id' => 'integer',
            'text' => 'string',
            'categories_id' => ArrayValue::class,
            'created_at' => TimeValue::class,
            'updated_at' => TimeValue::class,
            'created_by' => 'integer',
            'updated_by' => 'integer',

        ];
    }

    public function readOnlyFields() {
        return [
            'id',
            'created_by',
            'created_at',
        ];
    }
}