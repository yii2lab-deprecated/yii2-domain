<?php

namespace tests\unit\domain;

use tests\_source\entities\CityEntity;
use yii\base\InvalidArgumentException;
use yii2lab\extension\arrayTools\helpers\Collection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use tests\_source\entities\CountryEntity;
use tests\_source\entities\CurrencyEntity;
use yii2lab\test\Test\Unit;

class BaseEntityValidationTest extends Unit {
	
	public function testValidate() {
		$data = [
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
			'type' => CityEntity::TYPE_BIG,
		];
		$this->validate($data);
	}
	
	public function testValidateNullType() {
		$data = [
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
			'type' => null,
		];
		$this->validate($data);
	}
	
	public function testBadType() {
		$data = [
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
			'type' => '555',
		];
		$this->validate($data, [
			[
				'field' => 'type',
				'message' => 'Type is invalid.',
			],
		]);
	}
	
	public function testSetEmpty() {
		$data = [];
		$this->validate($data, [
			[
				'field' => 'country_id',
				'message' => 'Country Id cannot be blank.',
			],
			[
				'field' => 'region_id',
				'message' => 'Region Id cannot be blank.',
			],
			[
				'field' => 'name',
				'message' => 'Name cannot be blank.',
			],
		]);
	}
	
	private function validate($data, $message = null) {
		$entity = new CityEntity();
		$entity->load($data);
		if(empty($message)) {
			try {
				$entity->validate();
				$this->tester->assertNice();
			} catch(\Exception $e) {
				$this->tester->assertBad();
			}
		} else {
			try {
				$entity->validate();
				$this->tester->assertBad();
			} catch(UnprocessableEntityHttpException $e) {
				$this->tester->assertUnprocessableEntityExceptionMessage($message, $e);
			}
		}
	}
	
}
