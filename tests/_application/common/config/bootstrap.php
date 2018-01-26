<?php

use yii2lab\helpers\yii\FileHelper;

$testDir = FileHelper::up(__DIR__, 3);

Yii::setAlias('@tests', $testDir);
