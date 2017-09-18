<?php

namespace common\ddd\factories;

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
		return $this->createFactory(self::TYPE_SERVICE_LOCATOR, ServiceLocatorFactory::className());
	}

	public function getRepositoryLocator() {
		return $this->createFactory(self::TYPE_SERVICE_REPOSITORY, RepositoryLocatorFactory::className());
	}

	public function getEntity() {
		return $this->createFactory(self::TYPE_ENTITY, EntityFactory::className());
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
		return $this->createFactory(self::TYPE_MODEL, ModelFactory::className());
	}
	
	private function createFactory($type, $className = null) {
		$params = [
			'id' => $this->id,
			'type' => $type,
			'domain' => $this->domain,
		];
		if(empty($className)) {
			$className = BaseFactory::className();
		}
		return $this->createByClassName($className, $params);
	}
	
}
