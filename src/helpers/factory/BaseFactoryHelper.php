<?php

namespace yii2lab\domain\helpers\factory;

use Yii;
use yii2lab\domain\Domain;
use yii2lab\extension\common\helpers\ClassHelper;

abstract class BaseFactoryHelper {
	
	public static function createObject($id, $config, Domain $domain) {
		$config = RepositoryFactoryHelper::genConfig($id, $config, $domain);
		$instance = Yii::createObject($config);
		return $instance;
	}
	
	protected static function genConfigs($components, Domain $domain) {
		$configNew = [];
		foreach($components as $id => $config) {
			$configNew[$id] = static::genConfig($id, $config, $domain);
		}
		return $configNew;
	}
	
	public static function genConfig($id, $config, Domain $domain) {
		$resultConfig = [];
		if(is_array($config)) {
			$resultConfig = $config;
		} else {
			if(ClassHelper::isClass($config)) {
				$resultConfig['class'] = $config;
			}
		}
		if(empty($resultConfig['class'])) {
			$resultConfig['class'] = $domain->path . BSL . static::genClassName1($id, $config, $domain);
		}
		$resultConfig['id'] = $id;
		$resultConfig['domain'] = $domain;
		return $resultConfig;
	}
	
	abstract protected static function genClassName1($id, $config, Domain $domain);
	
}
