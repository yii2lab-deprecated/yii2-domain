<?php

namespace tests\unit\domain;

use tests\_source\entities\CityEntity;
use yii\base\InvalidArgumentException;
use yii2lab\domain\data\Collection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\geo\domain\entities\CountryEntity;
use yii2lab\geo\domain\entities\CurrencyEntity;
use yii2lab\test\Test\Unit;

class BaseEntityRelationTest extends Unit {
	
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
			'streets' => null,
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
			'streets' => null,
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
	
	public function testRelationBadCollection() {
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
	
	public function testRelationCollection() {
		$entity = new CityEntity();
		$streetEntity = new CurrencyEntity([
			'id' => '1',
			'name' => 'Buhar Zhirau',
		]);
		
		try {
			$entity->load([
				'id' => '7',
				'country_id' => '4',
				'region_id' => '5',
				'name' => 'Бендиго',
				'streets' => [$streetEntity],
			]);
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Object not instance of class', $e);
		}
	}
	
	public function testRelationCollectionOfEntity() {
		$entity = new CityEntity();
		$streetEntity = new CurrencyEntity([
			'id' => '1',
			'name' => 'Buhar Zhirau',
		]);
		
		try {
			$entity->load([
				'id' => '7',
				'country_id' => '4',
				'region_id' => '5',
				'name' => 'Бендиго',
				'streets' => $streetEntity,
			]);
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Need collection', $e);
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
			'streets' => null,
		];
		$this->tester->assertEquals($expected, $actual);
	}
}
