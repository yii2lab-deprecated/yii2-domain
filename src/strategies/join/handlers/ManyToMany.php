<?php

namespace yii2lab\domain\strategies\join\handlers;

use function PHPSTORM_META\elementType;
use yii\helpers\ArrayHelper;
use yii2lab\app\parent\App;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\dto\WithDto;
use yii2lab\domain\entities\relation\RelationEntity;
use yii2lab\domain\helpers\repository\RelationConfigHelper;
use yii2lab\domain\helpers\repository\RelationRepositoryHelper;
use yii2lab\extension\arrayTools\helpers\ArrayIterator;
use yii2woop\service\domain\v3\entities\CategoryEntity;
use yii2woop\service\domain\v3\services\CategoryService;

class ManyToMany extends Base implements HandlerInterface {
	
	public function join(array $collection, RelationEntity $relationEntity) {
		/** @var RelationEntity[] $viaRelations */
		$viaRelations = RelationConfigHelper::getRelationsConfig($relationEntity->via->domain, $relationEntity->via->name);
		$name = $relationEntity->via->self;
		$viaRelationToThis = $viaRelations[$name];
		$values = ArrayHelper::getColumn($collection, $viaRelationToThis->foreign->field);
		$query = Query::forge();
		$query->where($viaRelationToThis->field, $values);
		$relCollection = RelationRepositoryHelper::getAll($relationEntity->via, $query);
		return $relCollection;
	}
	
	public function load(BaseEntity $entity, WithDto $w, $relCollection): RelationEntity {
		$viaRelations = RelationConfigHelper::getRelationsConfig($w->relationConfig->via->domain, $w->relationConfig->via->name);
		/** @var RelationEntity $viaRelationToThis */
		$viaRelationToThis = $viaRelations[$w->relationConfig->via->self];
		/** @var RelationEntity $viaRelationToForeign */
		$viaRelationToForeign = $viaRelations[$w->relationConfig->via->foreign];
		$itemValue = $entity->{$viaRelationToForeign->foreign->field};
		$viaQuery = Query::forge();
		$viaQuery->where($viaRelationToThis->field, $itemValue);
		$viaData = ArrayIterator::allFromArray($viaQuery, $relCollection);
		$foreignIds = ArrayHelper::getColumn($viaData, $viaRelationToForeign->field);
		
		if(isset($viaRelationToForeign->foreign->query) && $viaRelationToForeign->foreign->query instanceof Query){
			$query = Query::forge($viaRelationToForeign->foreign->query);
		}else{
			$query = Query::forge();
		}
		$query->where($viaRelationToForeign->foreign->field, $foreignIds);
		
		// Этот небывалый костыль нужен был
		// для реализации этой фичи https://youtrack.wooppay.com/issue/PL-480 срочно!
		if($viaRelationToForeign->foreign->name == 'category'){
			$headers = \Yii::$app->request->getHeaders();
			$lang = isset($headers['language']) ? $headers['language'] : 'ru';
			// это нужно было бизнесу...
			if($lang != 'ru' ){
				$additinalQuery = Query::forgeClone($query);
				$additinalQuery->removeWhere('language');
				$additinalQuery->andWhere(['language' => 'rus']);
				$additinaldata = RelationRepositoryHelper::getAll($viaRelationToForeign->foreign, $additinalQuery);
			}
			
		}
		//конец костыля
		
		$data = RelationRepositoryHelper::getAll($viaRelationToForeign->foreign, $query);
		$data = self::prepareValue($data, $w);
		
		// продолжение того костыля
		if(isset($additinaldata)){
			$ruModelsArray = $additinaldata;
			$resultModelsArray = $data;
			foreach($ruModelsArray as $k1 => $ruCat){
				foreach($resultModelsArray as $k2 => $nativeCat){
					if($nativeCat->id == $ruCat->id){
						unset($ruModelsArray[$k1]);
					}
				}
			}
			$data = $resultModelsArray + $ruModelsArray;
		}
		//конец костыля
		
		$entity->{$w->relationName} = $data;
		return $viaRelationToForeign;
	}
}