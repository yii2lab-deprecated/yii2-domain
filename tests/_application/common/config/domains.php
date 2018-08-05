<?php

use yii\helpers\ArrayHelper;
use yii2lab\test\helpers\TestHelper;

$config = [
    'lang' => 'yii2module\lang\domain\Domain',
    'rbac' => 'yii2lab\rbac\domain\Domain',
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
