<?php

namespace yii2lab\domain\repositories;

use yii2lab\domain\Alias;
use yii2lab\domain\data\ActiveDataProvider;
use yii2lab\domain\data\Query;
use yii2lab\domain\Domain;
use yii2lab\domain\helpers\QueryValidator;
use yii2lab\domain\helpers\repository\QueryFilter;
use yii2lab\domain\interfaces\repositories\ReadInterface;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii2lab\domain\repositories\relations\BaseSchema;
use yii2lab\domain\traits\ReadEventTrait;
use yii2lab\extension\common\helpers\ClassHelper;

/**
 * Class Domain
 *
 * @package yii2lab\domain
 *
 * @property Alias $alias
 * @property QueryValidator $queryValidator
 * @property Domain $domain
 *
 */
abstract class BaseRepository extends Component {
	
	use ReadEventTrait;
	
	const SCENARIO_INSERT = 'insert';
	const SCENARIO_UPDATE = 'update';
	
	public $id;
	
	/** @var \yii2lab\domain\Domain */
	public $domain;
	private $alias;
	
	/** @var \yii2lab\domain\helpers\QueryValidator */
	private $queryValidator;
	public $driver;
	protected $primaryKey = 'id';
	protected $schemaClass = false;
	private $schemaInstance;
	
	protected function queryFilterInstance(Query $query = null) {
		$query = $this->prepareQuery($query);
		/** @var QueryFilter $queryFilter */
		$queryFilter = Yii::createObject([
			'class' => QueryFilter::class,
			'repository' => $this,
			'query' => $query,
		]);
		return $queryFilter;
	}
	
	protected function allWithRelation(Query $query = null, $callback) {
		$queryFilter = $this->queryFilterInstance($query);
		$queryWithoutRelations = $queryFilter->getQueryWithoutRelations();
		
		$models = $this->{$callback}($queryWithoutRelations);
		$collection = $this->forgeEntity($models);
		
		$collection = $queryFilter->loadRelations($collection);
		return $collection;
	}
	
	/**
	 * @param Query|null $query
	 *
	 * @return ActiveDataProvider
	 * @throws InvalidConfigException
	 */
	public function getDataProvider(Query $query = null) {
		if(!$this instanceof ReadInterface) {
			throw new InvalidConfigException("Repository " . static::class . " not implements of ReadInterface");
		}
		$query = $this->prepareQuery($query);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'service' => $this,
		]);
		$dataProvider->models = $this->afterReadTrigger($dataProvider->models, $query);
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
	
	public function searchByTextFields() {
		return $this->runSchemaMethod('searchByTextFields');
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
	
	/**
	 * @return Alias
	 */
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

    /**
     *
     * @throws InvalidConfigException
     * @throws \yii\web\ServerErrorHttpException
     */
    private function getSchemaInstance() {
        if(!isset($this->schemaInstance)) {
            $schemaClass = $this->getSchemaClassName();
            if(empty($schemaClass)) {
                return false;
            }
            $this->schemaInstance = ClassHelper::createObject($schemaClass, [], BaseSchema::class);
        }
        return $this->schemaInstance;
    }

    private function getSchemaClassName() {
        $schemaClass = false;
	    if(is_string($this->schemaClass)) {
            $schemaClass = $this->schemaClass;
        } elseif($this->schemaClass === true) {
            $namespace = $this->domain->path . '\\repositories\\schema\\';
            $schemaClass = $namespace . ucfirst($this->id) . 'Schema';
        }
        return $schemaClass;
    }

	private function runSchemaMethod($methodName) {
        $schemaInstance = $this->getSchemaInstance();
        if(empty($schemaInstance)) {
            return [];
        }
		return $schemaInstance->$methodName();
	}
	
}