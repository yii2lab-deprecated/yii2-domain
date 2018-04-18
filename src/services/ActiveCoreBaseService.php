<?php

namespace yii2lab\domain\services;

use yii2lab\core\domain\repositories\base\BaseActiveCoreRepository;
use yii2lab\helpers\ClassHelper;

class ActiveCoreBaseService extends ActiveBaseService {
	
	public $point = EMP;
	/**
	 * @var BaseActiveCoreRepository
	 */
	private $coreRepository;
	
	/**
	 * @param null $name
	 *
	 * @return mixed
	 */
	public function getRepository($name = null) {
		
		if(!isset($this->coreRepository)) {
			$this->coreRepository = ClassHelper::createObject([
				'class' => BaseActiveCoreRepository::class,
				'domain' => $this->domain,
				'id' => $this->id,
				'point' => $this->point,
			]);
		}
		return $this->coreRepository;
	}
	
}