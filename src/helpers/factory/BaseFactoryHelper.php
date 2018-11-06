<?php

namespace yii2lab\domain\helpers\factory;

use Yii;
use yii2lab\domain\Domain;
use yii2lab\extension\common\helpers\ClassHelper;

abstract class BaseFactoryHelper {
	
	public static function createObject($id, $definition, Domain $domain) {
		$definition = static::genConfig($id, $definition, $domain);
		$instance = Yii::createObject($definition);
		return $instance;
	}
	
	public static function genConfigs($definitions, Domain $domain) {
		$definitionNew = [];
		foreach($definitions as $id => $definition) {
			$resultDefinition = static::genConfig($id, $definition, $domain);
			$definitionNew[$id] = $resultDefinition;
		}
		return $definitionNew;
	}
	
	protected static function genConfig($id, $definition, Domain $domain) {
		$resultDefinition = self::normalizeDefinition($definition);
		if(empty($resultDefinition['class'])) {
			$path = $domain->path ? $domain->path : ClassHelper::getNamespace(get_class($domain));
			$resultDefinition['class'] = $path . BSL . static::genClassName($id, $definition, $domain);
		}
		$resultDefinition['id'] = $id;
		$resultDefinition['domain'] = $domain;
		return $resultDefinition;
	}
	
	protected static function normalizeDefinition($definition) {
		if(!is_array($definition)) {
			if(ClassHelper::isClass($definition)) {
				$definition = [
					'class' => $definition,
				];
			} else {
				$definition = [];
			}
		}
		return $definition;
	}
	
	abstract protected static function genClassName($id, $definition, Domain $domain);
	
}
