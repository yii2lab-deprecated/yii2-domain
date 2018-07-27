<?php
namespace tests\unit\data;

use yii2lab\test\Test\Unit;
use yii2lab\extension\arrayTools\helpers\ArrayIterator;
use yii2lab\domain\data\Query;
use yii2lab\test\helpers\DataHelper;

class ArrayIteratorTest extends Unit
{
	
	const PACKAGE = 'yii2lab/yii2-domain';
	
	public function testOne()
	{
		$expect = [
			'id' => 2000,
			'country_id' => 1894,
			'region_id' => 1994,
		];
		
		$array = DataHelper::load(self::PACKAGE, '_fixtures/data/geo_city.php');
		$query = Query::forge();
		$query->where('id', 2000);
		
		$item = ArrayIterator::oneFromArray($query, $array);
		$this->tester->assertArraySubset($expect, $item);
		
		$iterator = new ArrayIterator();
		$iterator->setCollection($array);
		$item =  $iterator->one($query);
		$this->tester->assertArraySubset($expect, $item);
	}
	
	public function testAll()
	{
		$expect = [
			0 => [
				'id' => 1995,
				'country_id' => 1894,
				'region_id' => 1994,
			],
			7 => [
				'id' => 2002,
				'country_id' => 1894,
				'region_id' => 1994,
			],
			14 => [
				'id' => 2009,
				'country_id' => 1894,
				'region_id' => 1994,
			],
		];
		
		$array = DataHelper::load(self::PACKAGE, '_fixtures/data/geo_city.php');
		$query = Query::forge();
		$query->where('region_id', 1994);
		
		$all = ArrayIterator::allFromArray($query, $array);
		$this->tester->assertArraySubset($expect, $all);
		
		$iterator = new ArrayIterator();
		$iterator->setCollection($array);
		$all =  $iterator->all($query);
		$this->tester->assertArraySubset($expect, $all);
	}
	
	public function testCount()
	{
		$array = DataHelper::load(self::PACKAGE, '_fixtures/data/geo_city.php');
		$query = Query::forge();
		$query->where('region_id', 1994);
		
		$all = ArrayIterator::allFromArray($query, $array);
		$this->tester->assertCount(15, $all);
	}
	
	public function testSort()
	{
		$expect = [
			0 => [
				'id' => 2009,
			],
			7 => [
				'id' => 2002,
			],
			14 => [
				'id' => 1995,
			],
		];
		
		$array = DataHelper::load(self::PACKAGE, '_fixtures/data/geo_city.php');
		$query = Query::forge();
		$query->where('region_id', 1994);
		$query->orderBy(['id' => SORT_DESC]);
		
		$iterator = new ArrayIterator();
		$iterator->setCollection($array);
		$all =  $iterator->all($query);
		$this->tester->assertArraySubset($expect, $all);
	}
	
}
