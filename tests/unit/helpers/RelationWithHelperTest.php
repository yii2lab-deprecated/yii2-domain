<?php
namespace yii2lab\domain\tests\unit\helpers;

use Codeception\Test\Unit;
use yii2lab\domain\helpers\RelationWithHelper;

class RelationWithHelperTest extends Unit
{
	
	public function testFetch()
	{
		$withArray = [
			'country.currency.country',
			'region.cities.country.currency',
			'region.cities.region',
		];
		$fields = RelationWithHelper::fetch($withArray, $withTrimmedArray);
		expect($withTrimmedArray)->equals([
			'country' => [
				'currency.country',
			],
			'region' => [
				'cities.country.currency',
				'cities.region',
			],
		]);
		expect($fields)->equals([
			'country',
			'region',
		]);
	}
	
	public function testFetch1()
	{
		$withArray = [
			'region.cities.country.currency',
			'region.cities.region',
		];
		$fields = RelationWithHelper::fetch($withArray, $withTrimmedArray);
		expect($withTrimmedArray)->equals([
			'region' => [
				'cities.country.currency',
				'cities.region',
			],
		]);
		expect($fields)->equals([
			'region',
		]);
	}
	
	public function testFetch2()
	{
		$withArray = [
			'cities.country.currency',
			'cities.region',
		];
		$fields = RelationWithHelper::fetch($withArray, $withTrimmedArray);
		expect($withTrimmedArray)->equals([
			'cities' => [
				'country.currency',
				'region',
			],
		]);
		expect($fields)->equals([
			'cities',
		]);
	}
	
	public function testFetch3()
	{
		$withArray = [
			'country.currency',
			'region',
		];
		$fields = RelationWithHelper::fetch($withArray, $withTrimmedArray);
		expect($withTrimmedArray)->equals([
			'country' => [
				'currency',
			],
			'region' => [],
		]);
		expect($fields)->equals([
			'country',
			'region',
		]);
	}
	
	public function testToMap()
	{
		$withArray = [
			'country.currency.country',
			'region.cities.country.currency',
			'region.cities.region',
		];
		$map = RelationWithHelper::toMap($withArray);
		expect($map)->equals([
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
	}
	
}
