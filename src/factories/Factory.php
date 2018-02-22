<?php

namespace yii2lab\domain\factories;

/**
 * Class Factory
 *
 * @package yii2lab\domain\factories
 *
 * @property EntityFactory $entity
 * @property BaseFactory $helper
 * @property BaseFactory $factory
 * @property ServiceLocatorFactory $service_locator
 * @property RepositoryLocatorFactory $repository_locator
 * @property BaseFactory $form
 * @property ModelFactory $model
 */
class Factory extends BaseFactory {
	
	const TYPE_ENTITY = 'entity';
	const TYPE_HELPER = 'helper';
	const TYPE_FACTORY = 'factory';
	const TYPE_SERVICE = 'service';
	const TYPE_REPOSITORY = 'repository';
	const TYPE_FORM = 'form';
	const TYPE_MODEL = 'model';
	const TYPE_SERVICE_LOCATOR = 'serviceLocator';
	const TYPE_SERVICE_REPOSITORY = 'repositoryLocator';

	public function getServiceLocator() {
		return $this->createFactory(self::TYPE_SERVICE_LOCATOR, ServiceLocatorFactory::class);
	}

	public function getRepositoryLocator() {
		return $this->createFactory(self::TYPE_SERVICE_REPOSITORY, RepositoryLocatorFactory::class);
	}

	public function getEntity() {
		return $this->createFactory(self::TYPE_ENTITY, EntityFactory::class);
	}
	
	public function getHelper() {
		return $this->createFactory(self::TYPE_HELPER);
	}
	
	public function getService() {
		return $this->createFactory(self::TYPE_SERVICE);
	}
	
	public function getRepository() {
		return $this->createFactory(self::TYPE_REPOSITORY);
	}
	
	public function getForm() {
		return $this->createFactory(self::TYPE_FORM);
	}
	
	public function getModel() {
		return $this->createFactory(self::TYPE_MODEL, ModelFactory::class);
	}
	
	private function createFactory($type, $className = null) {
		$params = [
			'id' => $this->id,
			'type' => $type,
			'domain' => $this->domain,
		];
		if(empty($className)) {
			$className = BaseFactory::class;
		}
		return $this->createByClassName($className, $params);
	}
	
}
