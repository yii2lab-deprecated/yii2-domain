<?php

namespace yii2lab\domain\helpers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\UnknownPropertyException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\Domain;
use yii2lab\domain\entities\ServiceExecutorEntity;
use yii2mod\helpers\ArrayHelper;

class DomainHelper {
	
	public static function isDefinedDomain(string $domain) {
		if(empty($domain)) {
			throw new InvalidArgumentException('Domain name can not be empty!');
		}
		return Yii::$domain->has($domain);
	}
	
	public static function isDefinedService(string $domain, string $service) {
		if(!self::isDefinedDomain($domain)) {
			return false;
		}
		if(empty($service)) {
			throw new InvalidArgumentException('Service name can not be empty!');
		}
		try {
			Yii::$domain->{$domain}->{$service};
			return true;
		} catch(UnknownPropertyException $e) {
		}
		return false;
	}
	
	public static function getService(string $domain, string $service) {
		if(!self::isDefinedService($domain, $service)) {
			throw new InvalidArgumentException('Service "' . $domain . '->' . $service . '" not defined!');
		}
		return Yii::$domain->{$domain}->{$service};
	}
	
	public static function runService(ServiceExecutorEntity $entity) {
		$service = self::getService($entity->domain, $entity->name);
		$response = call_user_func_array([$service, $entity->method], $entity->params);
		return $response;
	}
	
	public static function define($domainId, $definition) {
		$definition = ConfigHelper::normalizeItemConfig($domainId, $definition);
		if(!Yii::$domain->has($domainId)) {
			Yii::$domain->set($domainId, $definition);
		}
	}
	
	public static function getClassConfig(string $domainId, $className, array $classDefinition = null) {
		$definition = self::getConfigFromDomainClass($className);
		$definition = ConfigHelper::normalizeItemConfig($domainId, $definition);
		if(!empty($classDefinition)) {
			$classDefinition =  ConfigHelper::normalizeItemConfig($domainId, $classDefinition);
			$definition = ArrayHelper::merge($definition, $classDefinition);
		}
		$definition['class'] = $className;
		return $definition;
	}
	
	private static function getConfigFromDomainClass($className) {
		$definition = ClassHelper::normalizeComponentConfig($className);
		/** @var Domain $domain */
		$domain = Yii::createObject($definition);
		$config = $domain->config();
		return $config;
	}
	
	public static function isEntity($data) {
		return is_object($data) && $data instanceof BaseEntity;
	}
	
	public static function isCollection($data) {
		return is_array($data);
	}
	
	public static function has($name) {
		if(!Yii::$app->has($name)) {
			return false;
		}
		$domain = !Yii::$app->get($name);
		if(!$domain instanceof Domain) {
			return false;
		}
		return true;
	}
	
	public static function messagesAlias($bundleName) {
		if(!Yii::$app->has($bundleName)) {
			return false;
		}
		$domain = ArrayHelper::getValue(Yii::$app, $bundleName);
		if(empty($domain) || empty($domain->path)) {
			return null;
		}
		return Helper::getBundlePath($domain->path . SL . 'messages');
	}
	
}