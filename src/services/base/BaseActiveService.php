<?php

namespace yii2lab\domain\services\base;

use yii\base\InvalidArgumentException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\interfaces\services\CrudInterface;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use Yii;
use yii\base\ActionEvent;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii2lab\domain\data\ActiveDataProvider;
use yii2lab\domain\interfaces\repositories\ReadInterface;

/**
 * Class ActiveBaseService
 *
 * @package yii2lab\domain\services
 *
 * @property-read \yii2lab\domain\interfaces\repositories\CrudInterface $repository
 */
class BaseActiveService extends BaseService implements CrudInterface {
	
	const EVENT_INDEX = 'index';
	const EVENT_CREATE = 'create';
	const EVENT_VIEW = 'view';
	const EVENT_UPDATE = 'update';
	const EVENT_DELETE = 'delete';
	
	/** @var \yii2lab\domain\BaseEntity */
	public $foreignServices;
	public $forbiddenChangeFields;
	
	/** @var bool private data for user */
	public $userAccessOnly = false;
	public $userIdField = 'user_id';
	
	public function getDataProvider(Query $query = null) {
		$query = Query::forge($query);
		$isReadInterface = $this->repository instanceof ReadInterface;
		$isMethodExists = method_exists($this->repository, 'getDataProvider');
		if($isReadInterface && $isMethodExists) {
			$dataProvider = $this->repository->getDataProvider($query);
		} else {
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'service' => $this,
			]);
		}
		return $dataProvider;
	}
	
	private function userAccessOnly(Query $query) {
		if($this->userAccessOnly) {
			$userId = Yii::$app->user->identity->id;
			$query->where($this->userIdField, "$userId");
		}
	}
	
	protected function addUserId(BaseEntity $entity) {
		if($this->userAccessOnly) {
			$userId = Yii::$app->user->identity->id;
			$entity->{$this->userIdField} = $userId;
		}
	}
	
	public function isExistsById($id) {
		return $this->repository->isExistsById($id);
	}
	
	public function isExists($condition) {
		return $this->repository->isExists($condition);
	}
	
	public function one(Query $query = null) {
		$this->beforeAction(self::EVENT_VIEW);
		$query = Query::forge($query);
		$this->userAccessOnly($query);
		$result = $this->repository->one($query);
		if(empty($result)) {
			throw new NotFoundHttpException(__METHOD__ . ':' . __LINE__);
		}
		return $this->afterAction(self::EVENT_VIEW, $result);
	}
	
	/**
	 * @param            $id
	 * @param Query|null $query
	 *
	 * @return \yii2lab\domain\BaseEntity $entity
	 * @throws NotFoundHttpException
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function oneById($id, Query $query = null) {
		if(empty($id)) {
			throw new InvalidArgumentException('ID can not be empty in ' . __METHOD__ . ' ' . static::class);
		}
		$this->beforeAction(self::EVENT_VIEW);
		$query = Query::forge($query);
		$this->userAccessOnly($query);
		$result = $this->repository->oneById($id, $query);
		if(empty($result)) {
			throw new NotFoundHttpException(__METHOD__ . ':' . __LINE__);
		}
		return $this->afterAction(self::EVENT_VIEW, $result);
	}
	
	public function count(Query $query = null) {
		$this->beforeAction(self::EVENT_INDEX);
		$query = Query::forge($query);
		$this->userAccessOnly($query);
		$result = $this->repository->count($query);
		return $this->afterAction(self::EVENT_INDEX, $result);
	}
	
	public function all(Query $query = null) {
		$this->beforeAction(self::EVENT_INDEX);
		$query = Query::forge($query);
		$this->userAccessOnly($query);
		$result = $this->repository->all($query);
		return $this->afterAction(self::EVENT_INDEX, $result);
	}
	
	public function create($data) {
		$this->beforeAction(self::EVENT_CREATE);
		$data = ArrayHelper::toArray($data);
		$this->validateForeign($data);
		$this->validateForbiddenChangeFields($data);
		/** @var \yii2lab\domain\BaseEntity $entity */
		$entity = $this->domain->factory->entity->create($this->id, $data);
		
		$entity->validate();
		$this->addUserId($entity);
		$entity = $this->repository->insert($entity);
		return $this->afterAction(self::EVENT_CREATE, $entity);
	}
	
	// todo: протестить
	public function update(BaseEntity $entity) {
		$this->beforeAction(self::EVENT_UPDATE);
		$data = ArrayHelper::toArray($entity);
		$this->validateForeign($data);
		$this->validateForbiddenChangeFields($data);
		$entity->validate();
		$this->addUserId($entity);
		$this->repository->update($entity);
		return $this->afterAction(self::EVENT_UPDATE);
	}
	
	public function updateById($id, $data) {
		$this->beforeAction(self::EVENT_UPDATE);
		$data = ArrayHelper::toArray($data);
		$this->validateForeign($data);
		$this->validateForbiddenChangeFields($data);
		$entity = $this->oneById($id);
		$entity->load($data);
		$entity->validate();
		$this->addUserId($entity);
		$this->repository->update($entity);
		return $this->afterAction(self::EVENT_UPDATE);
	}
	
	public function deleteById($id) {
		$this->beforeAction(self::EVENT_DELETE);
		$entity = $this->oneById($id);
		$this->addUserId($entity);
		$this->repository->delete($entity);
		return $this->afterAction(self::EVENT_DELETE);
	}
	
	protected function ensureForeignConfig($config) {
		foreach($config as $service => &$serviceConfig) {
			if(empty($serviceConfig['field'])) {
				throw new InvalidConfigException('The "foreignServices.field" property must be set.');
			}
			if(empty($serviceConfig['notFoundMessage'])) {
				$serviceConfig['notFoundMessage'] = ['domain/service', 'foreign_entity_not_found'];
			}
		}
		return $config;
	}
	
	protected function validateForbiddenChangeFields($data) {
		if(empty($this->forbiddenChangeFields)) {
			return;
		}
		foreach($this->forbiddenChangeFields as $fieldName) {
			if(!empty($data[ $fieldName ])) {
				$error = new ErrorCollection();
				$error->add($fieldName, 'domain/service', 'forbidden_change_field {field}', ['field' => $fieldName]);
				throw new UnprocessableEntityHttpException($error);
			}
		}
	}
	
	protected function validateForeign($data) {
		if(empty($this->foreignServices)) {
			return;
		}
		$config = $this->ensureForeignConfig($this->foreignServices);
		foreach($config as $serviceKey => $serviceConfig) {
			$fieldName = $serviceConfig['field'];
			if(!empty($serviceConfig['isChild'])) {
				continue;
			}
			if(!empty($data[ $fieldName ])) {
				try {
					$serviceInstance = ArrayHelper::getValue(Yii::$app, $serviceKey);
					$entity = $serviceInstance->oneById($data[ $fieldName ]);
				} catch(NotFoundHttpException $e) {
					$notFoundMessage = $serviceConfig['notFoundMessage'];
					$error = new ErrorCollection();
					$error->add($fieldName, $notFoundMessage[0], $notFoundMessage[1]);
					throw new UnprocessableEntityHttpException($error);
				}
			}
		}
	}

	protected function beforeAction($action) {
		$this->checkAccess($action, $this->access());
		$event = new ActionEvent($action);
		$this->trigger($action, $event);
		if(!$event->isValid) {
			throw new ServerErrorHttpException('Service method "' . $action . '" not allow!');
		}
	}
	
	protected function afterAction($action, $result = null) {
		$event = new ActionEvent($action);
		$event->result = $result;
		$this->trigger($action, $event);
		return $event->result;
	}

	private function checkAccess($action, $accessList = null, $param = null) {
		if(!$accessList) {
			return true;
		}
		foreach($accessList as $access) {
			$this->checkAccessRule($action, $access, $param);
		}
		return true;
	}

	private function checkAccessRule($action, $access, $param = null) {
		$access['only'] = !empty($access['only']) ? ArrayHelper::toArray($access['only']) : null;
		$isIntersectAction = empty($access['only']) || in_array($action, $access['only']);
		if($isIntersectAction) {
			Yii::$domain->rbac->manager->can($access['roles'], $param);
		}
	}

}