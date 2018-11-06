<?php
namespace tests\unit\helpers;

use yii2lab\test\Test\Unit;
use yii2lab\domain\helpers\repository\RelationWithHelper;

class RelationWithHelperTest extends Unit
{
	
	public function testFetch()
	{
		$withTrimmedArray = [];
		$withArray = [
			'country.currency.country',
			'region.cities.country.currency',
			'region.cities.region',
		];
		$fields = RelationWithHelper::fetch($withArray, $withTrimmedArray);
		$this->tester->assertEquals($withTrimmedArray, [
			'country' => [
				'currency.country',
			],
			'region' => [
				'cities.region',
				'cities.country.currency',
			],
		]);
		$this->tester->assertEquals($fields, [
			'region',
			'country',
		]);
		
		$withArray = $withTrimmedArray['region'];
		$withTrimmedArray = [];
		$fields = RelationWithHelper::fetch($withArray, $withTrimmedArray);
		$this->tester->assertEquals($withTrimmedArray, [
			'cities' => [
				'region',
				'country.currency',
			],
		]);
		$this->tester->assertEquals($fields, [
			'cities',
		]);
		
		$withArray = $withTrimmedArray['cities'];
		$withTrimmedArray = [];
		$fields = RelationWithHelper::fetch($withArray, $withTrimmedArray);
		$this->tester->assertEquals($withTrimmedArray, [
			'country' => [
				'currency',
			],
			'region' => [],
		]);
		$this->tester->assertEquals($fields, [
			'region',
			'country',
		]);
		
		$withArray = $withTrimmedArray['country'];
		$withTrimmedArray = [];
		$fields = RelationWithHelper::fetch($withArray, $withTrimmedArray);
		$this->tester->assertEquals($withTrimmedArray, [
			'currency' => [],
		]);
		$this->tester->assertEquals($fields, [
			'currency',
		]);
	}
	
	/*public function testToMap()
	{
		$withArray = [
			'country.currency.country',
			'region.cities.country.currency',
			'region.cities.region',
		];
		$map = RelationWithHelper::toMap($withArray);
		$this->tester->assertEquals($map, [
			'country' => [
				'currency' => [
					'country' => [],
				],
			],
			'region' => [
				'cities' => [
					'country' => [
						'currency' => [],
					],
					'region' => [],
				],
			],
		]);
	}*/
	
}
