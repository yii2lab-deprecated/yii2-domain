<?php

namespace yii2lab\domain;

use yii\helpers\ArrayHelper;
use yii2lab\domain\factories\Factory;
use Yii;
use yii\base\BaseObject;
use yii\base\UnknownPropertyException;
use yii2lab\domain\locators\Base;
use yii2lab\helpers\Helper;

/**
 * Class Domain
 *
 * @package yii2lab\domain
 *
 * @property string $id
 * @property string $path
 * @property string $defaultDriver
 * @property array $container
 * @property Base $services
 * @property Base $repositories
 * @property Factory $factory
 */
class Domain extends BaseObject {
	
	private $repositoryLocator = [];
	private $serviceLocator = [];
	private $_factory;
	
	protected $repositories = [];
	
	public $id;
	public $path;
	public $defaultDriver = 'ar';
	public $container = [];
	public $services = [];
	public $translations = [];
	
	public function init() {
		$this->initPath();
		$this->initId();
		$this->initContainer();
	}
	
	public function __get($name) {
		try {
			$value = parent::__get($name);
			return $value;
		} catch(UnknownPropertyException $e) {
			$this->initServices();
			return $this->serviceLocator->{$name};
		}
	}
	
	public function getFactory() {
		$this->init();
		if(!isset($this->_factory)) {
			$this->_factory = Yii::createObject(Factory::className());
			$attributes = [
				'id' => $this->id,
				'domain' => $this,
			];
			Yii::configure($this->_factory, $attributes);
		}
		return $this->_factory;
	}
	
	public function setRepositories($components) {
		if(empty($components)) {
			$components = ArrayHelper::getValue($this->config(), 'repositories');
		}
		$this->repositoryLocator =
			$this->
			getFactory()->
			repositoryLocator->
			create($this->id, $components);
	}
	
	public function getRepositories() {
		if(!is_object($this->repositoryLocator)) {
			$this->setRepositories([]);
		}
		return $this->repositoryLocator;
	}
	
	public function config() {
		return [];
	}
	
	private function initServices() {
		if(is_object($this->serviceLocator)) {
			return;
		}
		$components = $this->services;
		$this->serviceLocator =
			$this->
			getFactory()->
			serviceLocator->
			create($this->id, $components);
	}
	
	private function initContainer() {
		$definitions = $this->container;
		if(empty($definitions)) {
			return;
		}
		foreach($definitions as $class => $definition) {
			Yii::$container->set($class, $definition);
		}
	}
	
	private function initPath() {
		if(!empty($this->path)) {
			return;
		}
		if(!$this->isBaseClassName()) {
			$this->path = Helper::getNamespace(static::className());
		}
	}
	
	private function initId() {
		if(!empty($this->id)) {
			return;
		}
		$basename = basename($this->path);
		if(!$this->isBaseClassName() && $basename == 'Domain') {
			$dirname = dirname($this->path);
			$basename = basename($dirname);
		}
		$this->id = strtolower($basename);
	}
	
	private function isBaseClassName() {
		return static::className() == Domain::className();
	}
	
}