<?php
namespace yii2lab\domain\tests\unit\services;

use Codeception\Test\Unit;
use common\fixtures\ArticleCategoriesFixture;
use common\fixtures\ArticleCategoryFixture;
use common\fixtures\ArticleFixture;
use Yii;
use yii2lab\domain\BaseEntity;

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
		$entity = Yii::$app->article->article->oneById(1);
		expect(1)->equals($entity->id);
		expect('about')->equals($entity->name);
		expect('О нас')->equals($entity->title);
	}
	
}
