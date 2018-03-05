<?php

namespace yii2lab\domain\rest;

use Yii;
use yii\data\Pagination;
use yii2lab\helpers\TypeHelper;
use yii\rest\Serializer as YiiSerializer;

class Serializer extends YiiSerializer {
	
	public $format = [];
	
	public $offsetHeader = 'X-Pagination-Offset';
	
	protected function serializeModel($model) {
		$item = parent::serializeModel($model);
		if(!empty($item)) {
			$item = TypeHelper::serialize($model, $this->format);
		}
		return $item;
	}
	
	protected function serializeModels(array $models) {
		//$models = parent::serializeModels($models);
		foreach($models as &$item) {
			$item = TypeHelper::serialize($item, $this->format);
		}
		return $models;
	}
	
	protected function addPaginationHeaders($pagination)
	{
		$headers = $this->response->getHeaders();
		/** @var Pagination $pagination */
		$headers->set($this->totalCountHeader, $pagination->totalCount);
		$headers->set($this->pageCountHeader, $pagination->getPageCount());
		$headers->set($this->perPageHeader, $pagination->pageSize);
		$offset = Yii::$app->request->get('offset');
		if($offset !== null) {
			$offset = $offset < $pagination->totalCount ? $offset : $pagination->totalCount;
			$offset = intval($offset);
			$headers->set($this->offsetHeader, $offset);
		} else {
			$headers->set($this->currentPageHeader, $pagination->getPage() + 1);
			$headers->set($this->offsetHeader, $pagination->getOffset());
			$headers->set('Link', $this->generateLinks($pagination));
		}
	}
	
	private function generateLinks(Pagination $pagination) {
		$links = [];
		foreach ($pagination->getLinks(true) as $rel => $url) {
			$links[] = "<$url>; rel=$rel";
		}
		return implode(', ', $links);
	}
}
