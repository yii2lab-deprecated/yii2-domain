<?php
namespace tests\unit\helpers;

use Codeception\Test\Unit;
use yii2lab\domain\helpers\repository\RelationWithHelper;

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
		
		$withArray = $withTrimmedArray['region'];
		$withTrimmedArray = [];
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
		
		$withArray = $withTrimmedArray['cities'];
		$withTrimmedArray = [];
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
		
		$withArray = $withTrimmedArray['country'];
		$withTrimmedArray = [];
		$fields = RelationWithHelper::fetch($withArray, $withTrimmedArray);
		expect($withTrimmedArray)->equals([
			'currency' => [],
		]);
		expect($fields)->equals([
			'currency',
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
