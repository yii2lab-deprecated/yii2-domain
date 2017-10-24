<?php

namespace yii2lab\domain\interfaces\services;

interface ModifyInterface {
	
	/**
	 * @param $data array
	 *
	 * @throws \yii2lab\domain\exceptions\UnprocessableEntityHttpException
	 */
	public function create($data);

	/**
	 * @param $id
	 * @param $data array
	 *
	 * @throws \yii\web\NotFoundHttpException
	 * @throws \yii2lab\domain\exceptions\UnprocessableEntityHttpException
	 */
	public function updateById($id, $data);
	
	/**
	 * @param $id
	 *
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function deleteById($id);
	
}