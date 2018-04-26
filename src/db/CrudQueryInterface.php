<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii2lab\domain\db;

use yii2lab\domain\BaseEntity;

/**
 * @deprecated
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
