<?php

use yii2lab\test\helpers\TestHelper;

$mainConfig = @include('main.php');
return TestHelper::loadTestConfig($mainConfig);
