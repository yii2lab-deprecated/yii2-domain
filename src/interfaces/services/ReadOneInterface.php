<?php

namespace yii2lab\domain\interfaces\services;

use yii2lab\domain\data\Query;
use yii2woop\service\domain\v3\entities\ServiceEntity;

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
	 * @return ServiceEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function one(Query $query = null);
	
	/**
	 * @param            $id
	 * @param Query|null $query
	 *
	 * @return ServiceEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneById($id, Query $query = null);
	
}