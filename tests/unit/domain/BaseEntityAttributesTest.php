<?php

namespace tests\unit\domain;

use tests\_source\entities\CityEntity;
use yii\base\InvalidCallException;
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

    public function testSelectFields() {
        $entity = new CityEntity();
        $entity->load([
            'id' => '7',
            'country_id' => '4',
            'region_id' => '5',
            'name' => 'Бендиго',
        ]);
        $actual = $entity->toArray(['id', 'name', 'country_id']);
        $expected = [
            'id' => 7,
            'country_id' => 4,
            'name' => 'Бендиго',
        ];
        $this->tester->assertEquals($expected, $actual);
    }

    public function testEdited() {
        $entity = new CityEntity();
        $entity->load([
            'id' => '7',
            'country_id' => '4',
            'region_id' => '5',
            'name' => 'Бендиго',
        ]);

        $entity->country_id = 77;
        $entity->name = 'Бендиго111';

        $actual = $entity->modifiedFields();
        $expected = [
            'country_id',
            'name',
        ];
        $this->tester->assertEquals($expected, $actual);
    }

    public function testReadOnlyFields() {
        $entity = new CityEntity();
        $entity->load([
            'id' => '7',
            'country_id' => '4',
            'region_id' => '5',
            'name' => 'Бендиго',
        ]);

        $entity->country_id = 77;
        $entity->name = 'Бендиго111';
        try {
            $entity->id = 88;
            $this->tester->assertBad();
        } catch (InvalidCallException $e) {
            $this->tester->assertExceptionMessage('Setting read-only property: tests\_source\entities\CityEntity::id', $e);
        }

        try {
            $entity->load([
                'id' => '7',
            ]);
            $this->tester->assertBad();
        } catch (InvalidCallException $e) {
            $this->tester->assertExceptionMessage('Setting read-only property: tests\_source\entities\CityEntity::id', $e);
        }
    }
	
	public function testHideAttributes() {
		$entity = new CityEntity();
		$entity->load([
			'id' => '7',
			'country_id' => '4',
			'region_id' => '5',
			'name' => 'Бендиго',
		]);
		
		$entity->hideAttributes(['name']);
		$actual = $entity->toArray();
		$expected = [
			'id' => 7,
			'type' => null,
			'country_id' => 4,
			'region_id' => 5,
			'country' => null,
			'region' => null,
			'streets' => null,
			'created_at' => null,
		];
		$this->tester->assertEquals($expected, $actual);
		$this->tester->assertEquals('Бендиго', $entity->name);
		
		$actual = $entity->attributes();
		$expected = [
			'id',
			'type',
			'country_id',
			'region_id',
			'country',
			'region',
			'streets',
			'created_at',
			'hash',
		];
		$this->tester->assertEquals($expected, $actual);
	}
}
