<?php

namespace yii2lab\domain\interfaces\services;

use yii2lab\domain\BaseEntity;

interface ModifyInterface {
	
	/**
	 * @param $data array
	 *
	 * @throws \yii2lab\domain\exceptions\UnprocessableEntityHttpException
	 */
	public function create($data);
	
	//public function update(BaseEntity $entity);
	
	/**
	 * @param $id
	 * @param $data|BaseEntity array
	 *
	 * @throws \yii\web\NotFoundHttpException
	 * @throws \yii2lab\domain\exceptions\UnprocessableEntityHttpException
	 */
	
	public function updateById($id, $data);
	
	public function update(BaseEntity $entity);
	
	/**
	 * @param $id
	 *
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function deleteById($id);
	
}