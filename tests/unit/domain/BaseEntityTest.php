<?php

namespace tests\unit\domain;

use tests\_source\entities\CityEntity;
use yii\base\InvalidArgumentException;
use yii2lab\extension\arrayTools\helpers\Collection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use tests\_source\entities\CountryEntity;
use tests\_source\entities\CurrencyEntity;
use yii2lab\test\Test\Unit;

class BaseEntityTest extends Unit {
	
	public function testSetNotInteger() {
		$entity = new CityEntity();
		$data = [
			'id' => '7',
			'country_id' => 'rrrrrrrr',
			'region_id' => '5',
			'name' => 'Бендиго',
		];
		try {
			$entity->load($data);
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Value "rrrrrrrr" not valid of "IntegerType"!', $e);
		}
	}
	
	public function testSetValueObject() {
		$entity = new CityEntity();
		$entity->load([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
			'created_at' => 1532807984,
		]);
		$actual = $entity->toArray();
		$expected = [
			'id' => 7,
			'type' => null,
			'country_id' => 4,
			'region_id' => 5,
			'name' => 'Бендиго',
			'country' => null,
			'region' => null,
			'created_at' => '2018-07-28 19:59:44',
			'streets' => null,
		];
		$this->tester->assertEquals($expected, $actual);
	}
	
}
