<?php

use common\locators\DomainLocator;
use yii2lab\domain\base\BaseDomainLocator;

/**
 * Class Dii
 *
 * @deprecated
 */
class Dii
{
	
	/**
	 * @var DomainLocator|BaseDomainLocator the domain container
     * @deprecated use \App::$domain
     */
	public static $domain;
	
}
