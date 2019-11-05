<?php

use yii\helpers\ArrayHelper;
use yii2lab\test\helpers\TestHelper;
use yii2module\lang\domain\enums\LanguageEnum;

$config = [
	'language' => LanguageEnum::EN, // current Language
];

$baseConfig = TestHelper::loadConfig('common/config/main.php');
return ArrayHelper::merge($baseConfig, $config);
