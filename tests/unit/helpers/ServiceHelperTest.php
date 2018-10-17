<?php
namespace tests\unit\helpers;

use yii2lab\test\Test\Unit;
use yii\base\InvalidArgumentException;
use yii2lab\domain\entities\ServiceExecutorEntity;
use yii2lab\domain\helpers\ServiceHelper;
use yii2module\account\domain\v2\helpers\TestAuthHelper;

class ServiceHelperTest extends Unit
{
	
	public function testRun()
	{
		TestAuthHelper::defineAccountDomain();
		$executor = new ServiceExecutorEntity();
		$executor->domain = 'account';
		$executor->service = 'login';
		$executor->method = 'oneById';
		$executor->params = 381949;
		$entity = ServiceHelper::run($executor);
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
		$entity = ServiceHelper::run($executor);
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
			$entity = ServiceHelper::run($executor);
			$this->tester->assertBad($entity);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
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
			$entity = ServiceHelper::run($executor);
			$this->tester->assertBad($entity);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
			$this->tester->assertExceptionMessage('Service "account1->login" not defined!', $e);
		}
	}
	
	public function testIsExists()
	{
		TestAuthHelper::defineAccountDomain();
		
		$isDefined = ServiceHelper::isExists('account', 'login');
		$this->tester->assertTrue($isDefined);
		
		$isDefined = ServiceHelper::isExists('account1', 'login');
		$this->tester->assertFalse($isDefined);
		
		$isDefined = ServiceHelper::isExists('account', 'login1');
		$this->tester->assertFalse($isDefined);
		
		try {
			$isDefined = ServiceHelper::isExists('', 'login1');
			$this->tester->assertBad($isDefined);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
			$this->tester->assertExceptionMessage('Domain name can not be empty!', $e);
		}
		
		try {
			$isDefined = ServiceHelper::isExists('account', '');
			$this->tester->assertBad($isDefined);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
			$this->tester->assertExceptionMessage('Service name can not be empty!', $e);
		}
	}
	
	public function testGetService()
	{
		TestAuthHelper::defineAccountDomain();
		
		$isDefined = ServiceHelper::isExists('account', 'login');
		$this->tester->assertTrue($isDefined);
		
		$isDefined = ServiceHelper::isExists('account1', 'login');
		$this->tester->assertFalse($isDefined);
		
		$isDefined = ServiceHelper::isExists('account', 'login1');
		$this->tester->assertFalse($isDefined);
		
		try {
			$isDefined = ServiceHelper::isExists('', 'login1');
			$this->tester->assertBad($isDefined);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
			$this->tester->assertExceptionMessage('Domain name can not be empty!', $e);
		}
		
		try {
			$isDefined = ServiceHelper::isExists('account', '');
			$this->tester->assertBad($isDefined);
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
			$this->tester->assertExceptionMessage('Service name can not be empty!', $e);
		}
	}
	
}
