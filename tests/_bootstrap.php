<?php

use yii2lab\app\App;

$name = 'common';
define('YII_ENV', 'test');

require_once(realpath(__DIR__ . '/../../../yii2lab/yii2-app/src/App.php'));

App::init($name);
