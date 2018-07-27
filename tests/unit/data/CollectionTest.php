<?php

namespace tests\unit\data;

use yii2lab\test\Test\Unit;
use yii\base\ErrorException;
use yii2lab\domain\data\Collection;

class CollectionTest extends Unit {
	
	private $array = [
		'item1',
		'item2',
		'item3',
	];
	
	public function testArrayItem() {
		$collection = new Collection($this->array);
		expect($collection[0])->equals('item1');
		expect($collection[2])->equals('item3');
	}
	
	public function testToArray() {
		$collection = new Collection($this->array);
		expect($collection instanceof Collection)->true();
		
		$array = $collection->toArray();
		expect($array instanceof Collection)->false();
		expect(is_array($array))->true();
	}
	
	public function testForeach() {
		$collection = new Collection($this->array);
		$result = [];
		foreach($collection as $item) {
			$result[] = $item;
		}
		expect($result)->equals($this->array);
	}
	
	public function testRewind() {
		$collection = new Collection($this->array);
		expect($collection->fetch())->equals('item1');
		$collection->rewind();
		expect($collection->fetch())->equals('item1');
	}
	
	public function testNext() {
		$collection = new Collection($this->array);
		$collection->next();
		expect($collection->current())->equals('item2');
		$collection->next();
		expect($collection->current())->equals('item3');
		$collection->next();
		try {
			$collection->current();
			expect(false)->true();
		} catch(ErrorException $e) {
			expect(true)->true();
		}
	}
	
	public function testUnset() {
		$collection = new Collection($this->array);
		unset($collection[0]);
		$array = $collection->toArray();
		expect($array)->equals([
			1 => 'item2',
			2 => 'item3',
		]);
	}
	
	public function testAssign() {
		$collection = new Collection($this->array);
		$collection[0] = 'itemX';
		$array = $collection->toArray();
		expect($array)->equals([
			'itemX',
			'item2',
			'item3',
		]);
	}
	
	public function testCount() {
		$collection = new Collection($this->array);
		expect(count($collection))->equals(3);
	}
	
	public function testLoadFromArray() {
		$collection = new Collection();
		$collection->load($this->array);
		
		expect($collection->toArray())->equals($this->array);
		
		$collection->load($this->array);
		expect($collection->fetch())->equals('item1');
	}
	
	public function testLoadFromCollectionObject() {
		$collection = new Collection();
		$collection->load($this->array);
		
		$collection2 = new Collection();
		$collection2->load($collection);
		expect($collection->toArray())->equals($this->array);
	}
	
	public function testForge() {
		$collection = Collection::forge($this->array);
		expect($collection->toArray())->equals($this->array);
	}
	
	public function testFirst() {
		$collection = new Collection($this->array);
		expect($collection->first())->equals('item1');
	}
	
	public function testLast() {
		$collection = new Collection($this->array);
		expect($collection->last())->equals('item3');
	}
	
	public function testFetch() {
		$collection = new Collection($this->array);
		expect($collection->fetch())->equals('item1');
		expect($collection->fetch())->equals('item2');
		expect($collection->fetch())->equals('item3');
	}
	
	public function testOneByIndex() {
		$collection = new Collection($this->array);
		expect($collection->one(0))->equals('item1');
		expect($collection->one(2))->equals('item3');
	}
	
}
