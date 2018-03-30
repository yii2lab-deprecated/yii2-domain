<?php

namespace yii2lab\domain\yii2;

use yii\di\ServiceLocator;

/**
 * @property \yii2module\account\domain\v2\Domain $account
 * @property \yii2module\profile\domain\v2\Domain $profile
 * @property \yii2lab\notify\domain\Domain $notify
 * @property \yii2lab\navigation\domain\Domain $navigation
 * @property \yii2lab\rbac\domain\Domain $rbac
 * @property \yii2module\lang\domain\Domain $lang
 * @property \yii2lab\geo\domain\Domain $geo
 * @property \yii2module\vendor\domain\Domain $vendor
 * @property \yii2module\tool\domain\Domain $tool
 * @property \yii2module\encrypt\domain\Domain $encrypt
 * @property \yii2module\article\domain\Domain $article
 * @property \yii2lab\app\domain\Domain $app
 * @property \yii2module\guide\domain\Domain $guide
 */

class DomainContainer extends ServiceLocator {
}
