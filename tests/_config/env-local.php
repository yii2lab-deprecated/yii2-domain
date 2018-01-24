<?php

$config = require(ROOT_DIR . DS . TEST_APPLICATION_DIR . '/common/config/env-local.php');

return \yii\helpers\ArrayHelper::merge($config, [
	
]);