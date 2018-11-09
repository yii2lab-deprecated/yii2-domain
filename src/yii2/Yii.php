<?php

require VENDOR_DIR . DS . 'yiisoft' . DS . 'yii2' . '/BaseYii.php';

/**
 * Yii is a helper class serving common framework functionalities.
 *
 * It extends from [[\yii\BaseYii]] which provides the actual implementation.
 * By writing your own Yii class, you can customize some functionalities of [[\yii\BaseYii]].
 */
class Yii extends \yii\BaseYii
{

    /**
     * @var yii2lab\domain\base\BaseDomainLocator the domain container
     * @deprecated use \App::$domain
     */
	public static $domain;
	
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require VENDOR_DIR . DS . 'yiisoft' . DS . 'yii2' . '/classes.php';
Yii::$container = new yii\di\Container();