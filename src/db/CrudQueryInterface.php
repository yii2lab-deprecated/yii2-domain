<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\ddd\db;

use common\ddd\BaseEntity;

/**
 * The QueryInterface defines the minimum set of methods to be implemented by a database query.
 *
 * The default implementation of this interface is provided by [[QueryTrait]].
 *
 * It has support for getting [[one]] instance or [[all]].
 * Allows pagination via [[limit]] and [[offset]].
 * Sorting is supported via [[orderBy]] and items can be limited to match some conditions using [[where]].
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
interface CrudQueryInterface {
	
	public function all();
	
	public function one();
	
	public function delete(BaseEntity $entity);
	
	public function save(BaseEntity $entity);
	
	//public function update(BaseEntity $entity);
	public function findAll($condition);
	
	public function findOne($condition);
	
	public function where($condition);
	//public function andWhere($condition);
	//public function orWhere($condition);
	//public function filterWhere(array $condition);
	//public function andFilterWhere(array $condition);
	//public function orFilterWhere(array $condition);
	//public function getPrimaryKey($asArray = false);
	//public function primaryKey();
	
}
