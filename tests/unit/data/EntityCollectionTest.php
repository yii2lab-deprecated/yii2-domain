<?php

namespace tests\unit\data;

use tests\_source\entities\CityEntity;
use tests\_source\entities\StreetEntity;
use yii\base\InvalidArgumentException;
use yii2lab\domain\data\EntityCollection;
use yii2lab\test\Test\Unit;
use yii\base\ErrorException;
use yii2lab\extension\arrayTools\helpers\Collection;

class EntityCollectionTest extends Unit {
	
	
	public function testSuccess() {
		$entity = new CityEntity([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
		]);
		$array = [
			$entity,
		];
		$collection = new EntityCollection(CityEntity::class, $array);
		$this->tester->assertEquals($entity, $collection->fetch());
	}
	
	public function testArray() {
		$entity = new CityEntity([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
		]);
		
		$array = [
			[
				'id' => '7',
				'country_id' => '4',
				'region_id' => '5',
				'name' => 'Бендиго',
			],
		];
		$collection = new EntityCollection(CityEntity::class, $array);
		$collection[] = [
			'id' => '8',
			'country_id' => '5',
			'region_id' => '6',
			'name' => 'Бендиго111',
		];
		
		$this->tester->assertEquals($entity, $collection->fetch());
		$this->tester->assertEquals(new CityEntity([
			'id' => '8',
			'country_id' => '5',
			'region_id' => '6',
			'name' => 'Бендиго111',
		]), $collection->fetch());
	}
	
	public function testInvalidInstanceClass() {
		$entity = new CityEntity([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
		]);
		$array = [
			$entity,
		];
		
		try {
			new EntityCollection(StreetEntity::class, $array);
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Object not instance of class', $e);
		}
	}
	
	public function testNotFoundClass() {
		try {
			new EntityCollection('path\helpers\StreetEntity', []);
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Class not exists', $e);
		}
	}
	
	public function testEmptyClass() {
		try {
			new EntityCollection( '', []);
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertExceptionMessage('Class is empty', $e);
		}
	}
}
