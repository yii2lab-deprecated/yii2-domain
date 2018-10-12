<?php

use common\locators\DomainLocator;

/**
 * Class Dii
 *
 * @deprecated
 */
class Dii
{
	
	/**
	 * @var DomainLocator|\yii2lab\domain\locators\DomainLocator the domain container
     * @deprecated use \App::$domain
     */
	public static $domain;
	
}
