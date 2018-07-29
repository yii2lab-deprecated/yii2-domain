<?php

namespace tests\unit\domain;

use tests\_source\entities\CityEntity;
use yii\base\InvalidArgumentException;
use yii2lab\domain\data\Collection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\geo\domain\entities\CountryEntity;
use yii2lab\geo\domain\entities\CurrencyEntity;
use yii2lab\test\Test\Unit;

class BaseEntityTest extends Unit {
	
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
	
	public function testSetBadType() {
		$entity = new CityEntity();
		$data = [
			'id' => '7',
			'country_id' => 'rrrrrrrr',
			'region_id' => '5',
			'name' => 'Бендиго',
		];
		try {
			$entity->load($data);
			//$entity->validate();
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Value "rrrrrrrr" not integer!', $e);
		}
	}
	
	public function testBadRelation() {
		$entity = new CityEntity();
		try {
			$entity->load([
				'id' => '7',
				'country_id' => '4',
				'region_id' => '5',
				'name' => 'Бендиго',
				'country' => 123,
			]);
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Entity data not array or object!', $e);
		}
	}
	
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
			'created_at',
		];
		$this->tester->assertEquals($expected, $actual);
	}
	
	public function testRelation() {
		$entity = new CityEntity();
		$entity->load([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
			'country' => [
				'id' => '4',
				'name' => 'Австралия',
			],
		]);
		$actual = $entity->toArray();
		$expected = [
			'id' => 7,
			'type' => null,
			'country_id' => 4,
			'region_id' => 5,
			'name' => 'Бендиго',
			'country' => [
				'id' => '4',
				'name' => 'Австралия',
				'currency' => null,
			],
			'region' => null,
			'created_at' => null,
		];
		$this->tester->assertEquals($expected, $actual);
		
		$actual = $entity->country->toArray();
		$expected = [
			'id' => '4',
			'name' => 'Австралия',
			'currency' => null,
		];
		$this->tester->assertEquals($expected, $actual);
	}
	
	public function testRelationEntity() {
		$entity = new CityEntity();
		$countryEntity = new CountryEntity([
			'id' => '4',
			'name' => 'Австралия',
			'currency' => null,
		]);
		$entity->load([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
			'country' => $countryEntity,
		]);
		$actual = $entity->toArray();
		
		$expected = [
			'id' => 7,
			'type' => null,
			'country_id' => 4,
			'region_id' => 5,
			'name' => 'Бендиго',
			'country' => [
				'id' => '4',
				'name' => 'Австралия',
				'currency' => null,
			],
			'region' => null,
			'created_at' => null,
		];
		$this->tester->assertEquals($expected, $actual);
		
		$actual = $entity->country->toArray();
		$expected = [
			'id' => '4',
			'name' => 'Австралия',
			'currency' => null,
		];
		$this->tester->assertEquals($expected, $actual);
	}
	
	public function testBadRelationEntity() {
		$entity = new CityEntity();
		$countryEntity = new CurrencyEntity([
			'id' => '4',
			'name' => 'Австралия',
			'currency' => null,
		]);
		
		try {
			$entity->load([
				'id' => '7',
				'country_id' => '4',
				'region_id' => '5',
				'name' => 'Бендиго',
				'country' => $countryEntity,
			]);
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Object not instance of class', $e);
		}
	}
	
	public function testRelationCollection() {
		$entity = new CityEntity();
		$countryEntity = new CurrencyEntity([
			'id' => '4',
			'name' => 'Австралия',
			'currency' => null,
		]);
		
		try {
			$entity->load([
				'id' => '7',
				'country_id' => '4',
				'region_id' => '5',
				'name' => 'Бендиго',
				'country' => [$countryEntity],
			]);
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Need array of item for entity', $e);
		}
		
		$collection = new Collection();
		$collection->load([$countryEntity]);
		
		try {
			$entity->load([
				'id' => '7',
				'country_id' => '4',
				'region_id' => '5',
				'name' => 'Бендиго',
				'country' => $collection,
			]);
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Value can not be collection', $e);
		}
	}
	
	public function testEmptyRelation() {
		$entity = new CityEntity();
		$entity->load([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
			'country' => [],
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
		];
		$this->tester->assertEquals($expected, $actual);
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
			'hash' => '034ec8ea699813f41160a1f7f0fe72e5',
		];
		$this->tester->assertEquals($expected, $actual);
	}
	
	public function testToArrayRaw() {
		/*$entity = new CityEntity();
		$entity->load([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
			'country' => [
				'id' => '4',
				'name' => 'Австралия',
				'currency' => null,
			],
			'created_at' => 1532807984,
		]);
		$actual = $entity->toArrayRaw();
		$this->tester->assertInstanceOf($actual['created_at'], TimeValue::class);*/
		
		/*$expected = [
			'id' => 7,
			'country_id' => 4,
			'region_id' => 5,
			'name' => 'Бендиго',
			'country' => null,
			'region' => null,
			'created_at' => null,
		];
		$this->tester->assertEquals($expected, $actual);*/
	}
	
	private function validate($data, $message = null) {
		$entity = new CityEntity();
		$entity->load($data);
		if(empty($message)) {
			try {
				$entity->validate();
				$this->tester->assertTrue(true);
			} catch(\Exception $e) {
				$this->tester->assertTrue(false);
			}
		} else {
			try {
				$entity->validate();
				$this->tester->assertTrue(false);
			} catch(UnprocessableEntityHttpException $e) {
				$this->tester->assertUnprocessableEntityExceptionMessage($message, $e);
			}
		}
	}
	
}
