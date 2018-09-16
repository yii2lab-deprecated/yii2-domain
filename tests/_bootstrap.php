<?php

use yii2lab\test\helpers\TestHelper;

$name = 'console';
$path = '../../../..';
defined('YII_ENV') OR define('YII_ENV', 'test');

@include_once(__DIR__ . '/' . $path . '/vendor/yii2lab/yii2-app/src/App.php');

if(!class_exists(App::class)) {
	die('Run composer install');
}

App::init($name, __DIR__ . '/_application');

TestHelper::copySqlite(__DIR__);
