<?php

namespace yii2lab\domain\locators;

use yii2lab\domain\Domain;
use yii2lab\domain\repositories\BaseRepository;

/**
 * @method BaseRepository get($id)
 */
class RepositoryLocator extends \yii\di\ServiceLocator {
	
	/**
	 * @var Domain
	 */
	public $domain;
	
}