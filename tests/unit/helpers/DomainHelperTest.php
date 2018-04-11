<?php
namespace tests\unit\helpers;

use Codeception\Test\Unit;
use yii\base\InvalidArgumentException;
use yii2lab\domain\entities\ServiceExecutorEntity;
use yii2lab\domain\helpers\DomainHelper;
use yii2lab\test\base\_support\UnitTester;
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
	
	public function testRun()
	{
		TestAuthHelper::defineAccountDomain();
		$executor = new ServiceExecutorEntity();
		$executor->domain = 'account';
		$executor->name = 'login';
		$executor->method = 'oneById';
		$executor->params = 381949;
		$entity = DomainHelper::runService($executor);
		$this->tester->assertEntity([
			'id' => 381949,
			'login' => '77771111111',
		], $entity);
	}
	
	public function testRunByServiceId()
	{
		TestAuthHelper::defineAccountDomain();
		$executor = new ServiceExecutorEntity();
		$executor->id = 'account.login';
		$executor->method = 'oneById';
		$executor->params = 381949;
		$entity = DomainHelper::runService($executor);
		$this->tester->assertEntity([
			'id' => 381949,
			'login' => '77771111111',
		], $entity);
	}
	
	public function testRunFailServiceName()
	{
		TestAuthHelper::defineAccountDomain();
		$executor = new ServiceExecutorEntity();
		$executor->id = 'account.login1';
		$executor->method = 'oneById';
		$executor->params = 381949;
		try {
			$entity = DomainHelper::runService($executor);
			$this->tester->assertTrue(false);
		} catch(\yii\base\InvalidArgumentException $e) {
			$this->tester->assertTrue(true);
			$this->tester->assertExceptionMessage('Service "account->login1" not defined!', $e);
		}
	}
	
	public function testRunFailDomainName()
	{
		TestAuthHelper::defineAccountDomain();
		$executor = new ServiceExecutorEntity();
		$executor->id = 'account1.login';
		$executor->method = 'oneById';
		$executor->params = 381949;
		try {
			$entity = DomainHelper::runService($executor);
			$this->tester->assertTrue(false);
		} catch(\yii\base\InvalidArgumentException $e) {
			$this->tester->assertTrue(true);
			$this->tester->assertExceptionMessage('Service "account1->login" not defined!', $e);
		}
	}
	
	public function testIsDefinedService()
	{
		TestAuthHelper::defineAccountDomain();
		
		$isDefined = DomainHelper::isDefinedService('account', 'login');
		$this->tester->assertTrue($isDefined);
		
		$isDefined = DomainHelper::isDefinedService('account1', 'login');
		$this->tester->assertFalse($isDefined);
		
		$isDefined = DomainHelper::isDefinedService('account', 'login1');
		$this->tester->assertFalse($isDefined);
		
		try {
			$isDefined = DomainHelper::isDefinedService('', 'login1');
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertTrue(true);
			$this->tester->assertExceptionMessage('Domain name can not be empty!', $e);
		}
		
		try {
			$isDefined = DomainHelper::isDefinedService('account', '');
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertTrue(true);
			$this->tester->assertExceptionMessage('Service name can not be empty!', $e);
		}
	}
	
	public function testGetService()
	{
		TestAuthHelper::defineAccountDomain();
		
		$isDefined = DomainHelper::isDefinedService('account', 'login');
		$this->tester->assertTrue($isDefined);
		
		$isDefined = DomainHelper::isDefinedService('account1', 'login');
		$this->tester->assertFalse($isDefined);
		
		$isDefined = DomainHelper::isDefinedService('account', 'login1');
		$this->tester->assertFalse($isDefined);
		
		try {
			$isDefined = DomainHelper::isDefinedService('', 'login1');
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertTrue(true);
			$this->tester->assertExceptionMessage('Domain name can not be empty!', $e);
		}
		
		try {
			$isDefined = DomainHelper::isDefinedService('account', '');
			$this->tester->assertTrue(false);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertTrue(true);
			$this->tester->assertExceptionMessage('Service name can not be empty!', $e);
		}
	}
	
}
