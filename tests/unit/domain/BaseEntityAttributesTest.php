<?php

namespace tests\unit\domain;

use tests\_source\entities\CityEntity;
use yii\base\InvalidArgumentException;
use yii2lab\domain\data\Collection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\geo\domain\entities\CountryEntity;
use yii2lab\geo\domain\entities\CurrencyEntity;
use yii2lab\test\Test\Unit;

class BaseEntityAttributesTest extends Unit {
	
	public function testFields() {
		$entity = new CityEntity();
		$entity->load([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
		]);
		$actual = $entity->fields();
		$expected = [
			'id' => 'id',
			'country_id' => 'country_id',
			'region_id' => 'region_id',
			'name' => 'name',
			'country' => 'country',
			'region' => 'region',
			'created_at' => 'created_at',
			'type' => 'type',
			'streets' => 'streets',
		];
		$this->tester->assertEquals($expected, $actual);
	}
	
	public function testToArray() {
		$entity = new CityEntity();
		$entity->load([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
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
			'created_at' => null,
			'streets' => null,
		];
		$this->tester->assertEquals($expected, $actual);
	}
	
	public function testAttributes() {
		$entity = new CityEntity();
		$entity->load([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
		]);
		$actual = $entity->attributes();
		$expected = [
			'id',
			'type',
			'country_id',
			'region_id',
			'name',
			'country',
			'region',
			'streets',
			'created_at',
			'hash',
		];
		$this->tester->assertEquals($expected, $actual);
	}
	
	public function testExtra() {
		$entity = new CityEntity();
		$entity->load([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
		]);
		$actual = $entity->toArray([], ['hash']);
		$expected = [
			'id' => 7,
			'type' => null,
			'country_id' => 4,
			'region_id' => 5,
			'name' => 'Бендиго',
			'country' => null,
			'region' => null,
			'created_at' => null,
			'hash' => '048c62ba8b174e8b21ba5154f73caa06',
			'streets' => null
		];
		$this->tester->assertEquals($expected, $actual);
	}
}
