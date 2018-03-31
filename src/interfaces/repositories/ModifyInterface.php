<?php

namespace yii2lab\domain\interfaces\repositories;

use yii2lab\domain\BaseEntity;

interface ModifyInterface extends RepositoryInterface {
	
	/**
	 * @param BaseEntity $entity
	 *
	 * @throws \yii2lab\domain\exceptions\UnprocessableEntityHttpException
	 */
	public function insert(BaseEntity $entity);

	/**
	 * @param BaseEntity $entity
	 *
	 * @throws \yii2lab\domain\exceptions\UnprocessableEntityHttpException
	 */
	public function update(BaseEntity $entity);
	
	/**
	 * @param BaseEntity $entity
	 *
	 */
	public function delete(BaseEntity $entity);
	
}