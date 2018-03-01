<?php

namespace yii2lab\domain\interfaces\repositories;

use yii2lab\domain\data\Query;

interface ReadOneInterface {
	
	/*
	 * @param Query|null $query
	 *
	 * @return \yii2lab\domain\BaseEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	//public function one(Query $query = null);
	
	/**
	 * @param            $id
	 * @param Query|null $query
	 *
	 * @return \yii2lab\domain\BaseEntity
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function oneById($id, Query $query = null);
	
}