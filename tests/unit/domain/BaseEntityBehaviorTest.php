<?php

namespace tests\unit\domain;

use tests\_source\entities\PostEntity;
use yii2lab\domain\values\ArrayValue;
use yii2lab\extension\arrayTools\base\BaseCollection;
use yii2lab\test\Test\Unit;
use yii2module\account\domain\v2\helpers\TestAuthHelper;

class BaseEntityBehaviorTest extends Unit {
	
	public function testAuthor() {
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

		$this->tester->assertGreaterThan(date('Y-m-d H:i:s', 1532886079), $entity->created_at);
		$this->tester->assertEquals($entity->created_at, $entity->updated_at);
		sleep(1);
		$entity->text = 'qwerty123456';

		$this->tester->assertNotEquals($entity->created_at, $entity->updated_at);
	}

    public function testInvslidValue() {
        TestAuthHelper::authById(381070);
        $entity = new PostEntity();
        try {
            $entity->updated_at = 'wertyu';
            $this->tester->assertBad();
        } catch (\yii\base\InvalidArgumentException $e) {
            $this->tester->assertExceptionMessage('Invalid value in "ValueObject"', $e);
        }
    }

    public function testReadOnly() {
        TestAuthHelper::authById(381070);
        $entity = new PostEntity();

        try {
            $entity->created_by = 777;
            $this->tester->assertBad();
        } catch (\yii\base\InvalidCallException $e) {
            $this->tester->assertExceptionMessage('Setting read-only property: tests\_source\entities\PostEntity::created_by', $e);
        }

        try {
            $entity->created_at = TIMESTAMP;
            $this->tester->assertBad();
        } catch (\yii\base\InvalidCallException $e) {
            $this->tester->assertExceptionMessage('Setting read-only property: tests\_source\entities\PostEntity::created_at', $e);
        }
    }

    public function testArrayField()
    {
        TestAuthHelper::authById(381070);
        $entity = new PostEntity();

        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key4' => [
                'key4-1' => 'value4-1',
                'key4-2' => 'value4-2',
                'key4-3' => 'value4-3',
                'key4-4' => 'value4-4',
            ],
        ];
        $entity->categories_id = $expected;

        $this->tester->assertInstanceOf(BaseCollection::class, $entity->categories_id);
        $this->tester->assertEquals($expected['key1'], $entity->categories_id['key1']);
        $this->tester->assertEquals($expected['key4'], $entity->categories_id['key4']);
        $this->tester->assertEquals($expected['key4']['key4-2'], $entity->categories_id['key4']['key4-2']);
        $this->tester->assertEquals($expected, $entity->categories_id->toArray());
    }

    public function testArrayFieldModify()
    {
        TestAuthHelper::authById(381070);
        $entity = new PostEntity();

        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
            'key4' => [
                'key4-1' => 'value4-1',
                'key4-2' => 'value4-2',
                'key4-3' => 'value4-3',
                'key4-4' => 'value4-4',
            ],
        ];
        $entity->categories_id = $expected;

        unset($entity->categories_id['key2']);
        $this->tester->assertEquals([
            'key1' => 'value1',
            //'key2' => 'value2',
            'key3' => 'value3',
            'key4' => [
                'key4-1' => 'value4-1',
                'key4-2' => 'value4-2',
                'key4-3' => 'value4-3',
                'key4-4' => 'value4-4',
            ],
        ], $entity->categories_id->toArray());

        //$entity->categories_id->updateByKey('key'value1'', 'qwerty');
        $entity->categories_id['key5'] = 'qwerty';
        $this->tester->assertEquals('qwerty', $entity->categories_id['key5']);

        //$entity->categories_id->setByKey('key4.key4-4', 'value4-4');
        //$entity->categories_id['key4']['key4-4'] = 'value4-4';
        // $this->tester->assertEquals('value4-4', $entity->categories_id['key4']['key4-4']);
    }

    public function testArrayFieldNull()
    {
        TestAuthHelper::authById(381070);
        $entity = new PostEntity();

        $this->tester->assertEquals(null, $entity->categories_id);

        $entity->categories_id = [1,2,3];
        $this->tester->assertEquals([1,2,3], $entity->categories_id->all());

        $entity->categories_id = null;
        $this->tester->assertEquals(null, $entity->categories_id);
    }
}
