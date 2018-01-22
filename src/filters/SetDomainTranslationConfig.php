<?php

namespace yii2lab\domain\filters;

use yii2lab\app\domain\filters\config\LoadConfig;
use yii2lab\designPattern\filter\interfaces\FilterInterface;
use yii2module\lang\domain\helpers\DomainConfigHelper;

class SetDomainTranslationConfig extends LoadConfig implements FilterInterface {
	
	public function run($config) {
		$config = DomainConfigHelper::addTranslations($config);
		foreach($config['components'] as $component) {
			if(DomainConfigHelper::isDomain($component) && !empty($component['translations'])) {
				foreach($component['translations'] as $translationId => $translation) {
					$config['components']['i18n']['translations']['domain:' . $translationId] = DomainConfigHelper::normalizeTranslation($translation);
				}
			}
		}
		return $config;
	}
	
}
