<?php

namespace yii2lab\domain\helpers;

use yii2module\lang\domain\helpers\LangHelper;

class DomainLangHelper {
	
	public static function setDomainTranslationConfig($config, $domains) {
		//$config = self::addTranslations($config);
		foreach($domains as $domain) {
			if(!empty($domain['translations'])) {
				foreach($domain['translations'] as $translationId => $translation) {
					$translation = LangHelper::normalizeTranslation($translation);
					$config = self::addPrefix($config);
					$config['components']['i18n']['translations']['domain:' . $translationId] = $translation;
				}
			}
		}
		return $config;
	}
	
	private static function addPrefix($translationConfig) {
		if(empty($translationConfig['fileMap'])) {
			return $translationConfig;
		}
		foreach($translationConfig['fileMap'] as $alias => $file) {
			$translationConfig['fileMap']['domain:' . $alias] = $translationConfig['fileMap'][$alias];
			unset($translationConfig['fileMap'][$alias]);
		}
		return $translationConfig;
	}
	
	private static function addTranslations($config) {
		foreach($config as $name => &$data) {
			if(!empty($data['translations'])) {
				$config = self::addTranslation($config, $data['translations']);
				unset($data['translations']);
			}
		}
		return $config;
	}
	
	private static function addTranslation($config, $translations) {
		if(empty($translations)) {
			return $config;
		}
		foreach($translations as $id => $translationConfig) {
			$translationConfig = LangHelper::normalizeTranslation($translationConfig);
			$config['components']['i18n']['translations'][$id] = $translationConfig;
			$config['components']['i18n']['translations']['domain:' . $id] = self::addPrefix($translationConfig);
		}
		return $config;
	}
	
}