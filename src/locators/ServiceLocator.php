<?php

namespace yii2lab\domain\locators;

use yii2lab\domain\Domain;
use yii2lab\domain\services\base\BaseService;

/**
 *
 * @method BaseService get($id)
 *
 */
class ServiceLocator extends \yii\di\ServiceLocator {
	
	/**
	 * @var Domain
	 */
	public $domain;
	
}