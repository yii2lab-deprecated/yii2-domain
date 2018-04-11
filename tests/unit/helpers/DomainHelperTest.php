<?php
namespace tests\unit\helpers;

use Codeception\Test\Unit;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii2lab\domain\entities\ServiceExecutorEntity;
use yii2lab\domain\helpers\DomainHelper;
use yii2lab\domain\helpers\ServiceHelper;
use yii2lab\test\base\_support\UnitTester;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\helpers\TestAuthHelper;

/**
 * Class DomainHelperTest
 *
 * @package tests\unit\helpers
 *
 * @property UnitTester $tester
 */
class DomainHelperTest extends Unit
{
	
	public function testHas()
	{
		TestAuthHelper::defineAccountDomain();
		
		$isHas = DomainHelper::has('account');
		$this->tester->assertTrue($isHas);
		
		$isHas = DomainHelper::has('account1');
		$this->tester->assertFalse($isHas);
	}
	
	public function testIsEntity()
	{
		$entity = new LoginEntity();
		$isHas = DomainHelper::isEntity($entity);
		$this->tester->assertTrue($isHas);
		
		$entity = new Model();
		$isHas = DomainHelper::isEntity($entity);
		$this->tester->assertFalse($isHas);
		
		$isHas = DomainHelper::isEntity('string');
		$this->tester->assertFalse($isHas);
	}
	
	public function testIsCollection()
	{
		$isHas = DomainHelper::isCollection([]);
		$this->tester->assertTrue($isHas);
		
		$isHas = DomainHelper::isCollection('string');
		$this->tester->assertFalse($isHas);
	}
	
	public function testMessagesAlias()
	{
		TestAuthHelper::defineAccountDomain();
		
		$isHas = DomainHelper::messagesAlias('account');
		$this->tester->assertEquals($isHas, '@yii2module/account/domain/v2/messages');
	}
	
}
