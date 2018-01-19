<?php
namespace yii2lab\domain\tests\unit\services;

use Codeception\Test\Unit;
use common\fixtures\ArticleCategoriesFixture;
use common\fixtures\ArticleCategoryFixture;
use common\fixtures\ArticleFixture;
use UnitTester;
use Yii;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;

/**
 * Class ArticleTest
 *
 * @package yii2lab\domain\tests\unit\services
 *
 * @property UnitTester $tester
 */
class ArticleTest extends Unit
{
	
	public function _before()
    {
        $this->tester->haveFixtures([
	        'category' => [
		        'class' => ArticleCategoryFixture::className(),
		        //'dataFile' => '@tests/_fixtures/data/user.php'
	        ],
	        'categories' => [
		        'class' => ArticleCategoriesFixture::className(),
		        //'dataFile' => '@tests/_fixtures/data/user.php'
	        ],
        	'article' => [
                'class' => ArticleFixture::className(),
                //'dataFile' => '@tests/_fixtures/data/user.php'
            ],
        ]);
    }
    
	public function testAllWithCategories()
	{
		
		/** @var BaseEntity $collection */
		$query = Query::forge();
		$query->with('categories');
		$collection = Yii::$app->article->article->all($query);
		
		$this->tester->assertEntity([
			'id' => 1,
			'name' => 'about',
			'categories' => [
				[
					'id' => 1,
				],
			],
		], $collection[0]);
		
		$this->tester->assertEntity([
			'id' => 3,
			'name' => 'contact',
			'categories' => [
				[
					'id' => 1,
				],
				[
					'id' => 2,
				],
			],
		], $collection[2]);
	}
	
}
