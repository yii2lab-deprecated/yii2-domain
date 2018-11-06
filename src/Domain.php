<?php

namespace yii2lab\domain;

use yii\helpers\ArrayHelper;
use yii2lab\domain\enums\Driver;
use yii2lab\domain\factories\Factory;
use Yii;
use yii\base\BaseObject;
use yii\base\UnknownPropertyException;
use yii2lab\domain\helpers\factory\RepositoryFactoryHelper;
use yii2lab\domain\helpers\factory\ServiceFactoryHelper;
use yii2lab\domain\locators\RepositoryLocator;
use yii2lab\domain\locators\ServiceLocator;
use yii2lab\extension\common\helpers\ClassHelper;
use yii2lab\domain\services\base\BaseService;

/**
 * Class Domain
 *
 * @package yii2lab\domain
 *
 * @property string $id
 * @property string $path
 * @property string $defaultDriver
 * @property array $container
 * @property ServiceLocator $services
 * @property RepositoryLocator $repositories
 * @property Factory $factory
 *
 * @method BaseService get($id)
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
	public $primaryDriver;
	public $slaveDriver;
	
	public function init() {
		$this->initPath();
		$this->initId();
		$this->initDriver();
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
			$this->_factory = Yii::createObject(Factory::class);
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
		$this->repositoryLocator = new RepositoryLocator;
		$this->repositoryLocator->domain = $this;
		$this->repositoryLocator->components = RepositoryFactoryHelper::genConfigs($components, $this);
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
		$this->serviceLocator = new ServiceLocator;
		$this->serviceLocator->domain = $this;
		$this->serviceLocator->components = ServiceFactoryHelper::genConfigs($components, $this);
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
			$this->path = ClassHelper::getNamespace(static::class);
		}
	}
	
	private function initDriver() {
		if(!isset($this->primaryDriver)) {
			$this->primaryDriver = Driver::primary();
		}
		if(!isset($this->slaveDriver)) {
			$this->slaveDriver = Driver::slave();
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
		return static::class == Domain::class;
	}
	
}