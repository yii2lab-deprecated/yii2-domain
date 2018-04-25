<?php

namespace yii2lab\domain\services;

use yii2lab\core\domain\repositories\base\BaseCoreRepository;
use yii2lab\helpers\ClassHelper;

/**
 * Class CoreBaseService
 *
 * @package yii2lab\domain\services
 *
 * @property BaseCoreRepository $repository
 */
class CoreBaseService extends BaseService {
	
	public $point = EMP;
	/**
	 * @var BaseCoreRepository
	 */
	private $coreRepository;
	
	public function getRepository($name = null) {
		if(!isset($this->coreRepository)) {
			$this->coreRepository = ClassHelper::createObject([
				'class' => BaseCoreRepository::class,
				'domain' => $this->domain,
				'id' => $this->id,
				'point' => $this->point,
			]);
		}
		return $this->coreRepository;
	}
	
}