<?php

namespace tests\unit\data;

use tests\_source\entities\CityEntity;
use tests\_source\entities\StreetEntity;
use yii\base\InvalidArgumentException;
use yii2lab\domain\data\EntityCollection;
use yii2lab\test\Test\Unit;
use yii\base\ErrorException;
use yii2lab\extension\arrayTools\helpers\Collection;

class CollectionTest extends Unit {
	
	private $array = [
		'item1',
		'item2',
		'item3',
	];
	
	public function testArrayItem() {
		$collection = new Collection($this->array);
		$this->tester->assertEquals($collection[0], 'item1');
		$this->tester->assertEquals($collection[2], 'item3');
	}
	
	public function testToArray() {
		$collection = new Collection($this->array);
		$this->tester->assertTrue($collection instanceof Collection);
		
		$array = $collection->toArray();
		$this->tester->assertFalse($array instanceof Collection);
		$this->tester->assertTrue(is_array($array));
	}
	
	public function testForeach() {
		$collection = new Collection($this->array);
		$result = [];
		foreach($collection as $item) {
			$result[] = $item;
		}
		$this->tester->assertEquals($this->array, $result);
	}
	
	public function testRewind() {
		$collection = new Collection($this->array);
		$this->tester->assertEquals($collection->fetch(), 'item1');
		$collection->rewind();
		$this->tester->assertEquals($collection->fetch(), 'item1');
	}
	
	public function testNext() {
		$collection = new Collection($this->array);
		$collection->next();
		$this->tester->assertEquals($collection->current(), 'item2');
		$collection->next();
		$this->tester->assertEquals($collection->current(), 'item3');
		$collection->next();
		try {
			$collection->current();
			$this->tester->assertBad();
		} catch(ErrorException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testUnset() {
		$collection = new Collection($this->array);
		unset($collection[0]);
		$array = $collection->toArray();
		$this->tester->assertEquals([
			1 => 'item2',
			2 => 'item3',
		], $array);
	}
	
	public function testAssign() {
		$collection = new Collection($this->array);
		$collection[0] = 'itemX';
		$array = $collection->toArray();
		$this->tester->assertEquals($array, [
			'itemX',
			'item2',
			'item3',
		]);
	}
	
	public function testCount() {
		$collection = new Collection($this->array);
		$this->tester->assertEquals(count($collection), 3);
	}
	
	public function testLoadFromArray() {
		$collection = new Collection();
		$collection->load($this->array);
		
		$this->tester->assertEquals($collection->toArray(), $this->array);
		
		$collection->load($this->array);
		$this->tester->assertEquals($collection->fetch(), 'item1');
	}
	
	public function testLoadFromCollectionObject() {
		$collection = new Collection();
		$collection->load($this->array);
		
		$collection2 = new Collection();
		$collection2->load($collection);
		$this->tester->assertEquals($collection->toArray(), $this->array);
	}
	
	public function testForge() {
		$collection = Collection::forge($this->array);
		$this->tester->assertEquals($collection->toArray(), $this->array);
	}
	
	public function testFirst() {
		$collection = new Collection($this->array);
		$this->tester->assertEquals($collection->first(), 'item1');
	}
	
	public function testLast() {
		$collection = new Collection($this->array);
		$this->tester->assertEquals($collection->last(), 'item3');
	}
	
	public function testFetch() {
		$collection = new Collection($this->array);
		$this->tester->assertEquals($collection->fetch(), 'item1');
		$this->tester->assertEquals($collection->fetch(), 'item2');
		$this->tester->assertEquals($collection->fetch(), 'item3');
	}
	
	public function testOneByIndex() {
		$collection = new Collection($this->array);
		$this->tester->assertEquals($collection->offsetGet(0), 'item1');
		$this->tester->assertEquals($collection->offsetGet(2), 'item3');
	}

    public function testSerialize() {
        $collection = new Collection($this->array);
        $serialized = $collection->serialize();

        $collection2 = new Collection();
        $unserialized = $collection2->unserialize($serialized);

        $this->tester->assertEquals($collection->all(), $collection2->all());
    }
}
