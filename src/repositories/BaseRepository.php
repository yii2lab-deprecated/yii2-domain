<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\Alias;
use yii2lab\domain\data\ActiveDataProvider;
use yii2lab\domain\data\Query;
use yii2lab\domain\helpers\QueryValidator;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use Yii;
use yii\base\Component as YiiComponent;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii2lab\domain\repositories\relations\BaseSchema;
use yii2lab\helpers\ClassHelper;

/**
 * Class Domain
 *
 * @package yii2lab\domain
 *
 * @property Alias $alias
 * @property QueryValidator $queryValidator
 */
abstract class BaseRepository extends YiiComponent {
	
	const SCENARIO_INSERT = 'insert';
	const SCENARIO_UPDATE = 'update';
	
	public $id;
	
	/** @var \yii2lab\domain\Domain */
	public $domain;
	
	/** @var Alias */
	private $alias;
	
	/** @var \yii2lab\domain\helpers\QueryValidator */
	private $queryValidator;
	public $driver;
	protected $primaryKey = 'id';
	protected $schemaClass = false;
	protected $schemaInstance;
	
	/**
	 * @param Query|null $query
	 *
	 * @return ActiveDataProvider
	 * @throws InvalidConfigException
	 */
	public function getDataProvider(Query $query = null) {
		if(!$this instanceof ReadInterface) {
			throw new InvalidConfigException("Repository {$this->class} not implements of ReadInterface");
		}
		$query = Query::forge($query);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'service' => $this,
		]);
		return $dataProvider;
	}
	
	public function scenarios() {
		$all = $this->allFields();
		$autoIncrementField = $this->autoIncrementField();
		$autoIncrementField = $this->alias->decode($autoIncrementField);
		if($autoIncrementField) {
			$all = array_diff($all, [$autoIncrementField]);
		}
		$scenario[ self::SCENARIO_INSERT ] = $all;
		$insert = $all;
		$scenario[ self::SCENARIO_INSERT ] = $insert;
		return $scenario;
	}
	
	public function autoIncrementField() {
		return null;
	}
	
	public function allFields() {
		return [];
	}
	
	public function relations() {
		return $this->runSchemaMethod('relations');
	}
	
	public function uniqueFields() {
		return $this->runSchemaMethod('uniqueFields');
	}
	
	public function whereFields() {
		return $this->allFields();
	}
	
	public function sortFields() {
		return $this->allFields();
	}
	
	public function selectFields() {
		return $this->allFields();
	}
	
	public function fieldAlias() {
		return [];
	}
	
	public function getAlias() {
		if(!isset($this->alias)) {
			$this->alias = new Alias();
			$this->alias->setAliases($this->fieldAlias());
		}
		return $this->alias;
	}
	
	/**
	 * @return object|QueryValidator
	 * @throws InvalidConfigException
	 */
	public function getQueryValidator() {
		if(!isset($this->queryValidator)) {
			$this->queryValidator = Yii::createObject(QueryValidator::class);
			Yii::configure($this->queryValidator, ['repository' => $this]);
		}
		return $this->queryValidator;
	}
	
	/**
	 * @param      $data
	 * @param null $class
	 *
	 * @return array|\yii2lab\domain\BaseEntity
	 */
	public function forgeEntity($data, $class = null) {
		if(empty($data)) {
			return [];
		}
		if(empty($class)) {
			$class = $this->id;
		}
		$array = ArrayHelper::toArray($data);
		$array = $this->getAlias()->decode($array);
		return $this->domain->factory->entity->create($class, $array);
	}
	
	private function runSchemaMethod($methodName) {
		if(!isset($this->schemaInstance)) {
			$schemaClass = $this->schemaClass;
			if(empty($schemaClass)) {
				return [];
			}
			if($schemaClass === true) {
				$namespace = ClassHelper::getNamespace(static::class);
				$namespace = dirname($namespace) . '\\schema\\';
				$schemaClass = $namespace . ucfirst($this->id) . 'Schema';
			}
			$this->schemaInstance = new $schemaClass;
			if(!$this->schemaInstance instanceof BaseSchema) {
				return [];
			}
		}
		return $this->schemaInstance->$methodName();
	}
	
}