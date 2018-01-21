<?php

namespace yii2lab\app;

use yii2lab\app\domain\helpers\Config;
use yii2lab\app\domain\helpers\Env;
use yii2lab\helpers\yii\ArrayHelper;

Env::init('vendor/yii2lab/yii2-app/tests/store/app');
$definition = Env::get('config');
$config = @include('main.php');
return ArrayHelper::merge(Config::load($definition), $config);
