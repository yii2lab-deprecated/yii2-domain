<?php

namespace tests\unit\domain;

use tests\_source\entities\PostEntity;
use yii2lab\test\Test\Unit;
use yii2module\account\domain\v2\helpers\TestAuthHelper;

class BaseEntityBehaviorTest extends Unit {
	
	public function testUser() {
		TestAuthHelper::authById(381070);
		$entity = new PostEntity();
		$this->tester->assertEquals($entity->created_by, 381070);
		$this->tester->assertEquals($entity->updated_by, 381070);
		
		TestAuthHelper::authById(381949);
		$entity->text = 'qwerty123456';
		$this->tester->assertEquals($entity->created_by, 381070);
		$this->tester->assertEquals($entity->updated_by, 381949);
	}
	
	public function testSetEmpty() {
		TestAuthHelper::authById(381070);
		$entity = new PostEntity();
		
		$this->tester->assertGreaterThan(1532886079, $entity->created_at);
		$this->tester->assertEquals($entity->created_at, $entity->updated_at);
		sleep(1);
		$entity->text = 'qwerty123456';
		$this->tester->assertNotEquals($entity->created_at, $entity->updated_at);
	}
	
}