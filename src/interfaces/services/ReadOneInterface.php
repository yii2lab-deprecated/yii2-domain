<?php

namespace yii2lab\domain\interfaces\services;

use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;

interface ReadOneInterface {
	
	/**
	 * @param $id
	 *
	 * @return boolean
	 */
	public function isExistsById($id);
	
	/**
	 * @param $condition array
	 *
	 * @return boolean
	 */
	public function isExists($condition);
	
	/**
	 * @param Query|null $query
	 *
	 * @return BaseEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function one(Query $query = null);
	
	/**
	 * @param            $id
	 * @param Query|null $query
	 *
	 * @return BaseEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneById($id, Query $query = null);
	
}