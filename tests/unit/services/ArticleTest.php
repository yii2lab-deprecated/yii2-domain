<?php
namespace yii2lab\domain\tests\unit\services;

use Codeception\Test\Unit;
use common\fixtures\ArticleCategoriesFixture;
use common\fixtures\ArticleCategoryFixture;
use common\fixtures\ArticleFixture;
use Yii;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2module\article\domain\entities\ArticleEntity;
use yii2module\article\domain\entities\CategoryEntity;

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
	
	public function testFetch()
	{
		/** @var BaseEntity $entity */
		$query = Query::forge();
		$query->with('categories');
		$entity = Yii::$app->article->article->all($query);
		
		expect(true)->equals($entity[0] instanceof ArticleEntity);
		expect(1)->equals($entity[0]->id);
		expect('about')->equals($entity[0]->name);
		expect(true)->equals($entity[0]->categories[0] instanceof CategoryEntity);
		expect(1)->equals($entity[0]->categories[0]->id);
		
		expect(1)->equals($entity[2]->categories[0]->id);
		expect(2)->equals($entity[2]->categories[1]->id);
	}
	
}
