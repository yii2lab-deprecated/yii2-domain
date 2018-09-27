<?php

namespace yii2lab\domain\web\actions;

use Yii;
use yii2lab\domain\base\Action;
use yii2lab\domain\data\ActiveDataProvider;
use yii2lab\domain\data\Query;

class IndexAction extends Action {
	
	public $serviceMethod = 'getDataProvider';
	
	public function run() {
		$this->view->title = Yii::t('main', 'list_title');
		$method = $this->serviceMethod;
		/** @var ActiveDataProvider $dataProvider */
		$dataProvider = $this->service->$method($this->query);
		//$dataProvider->query = Query::forge($this->query);
		return $this->render($this->render, compact('dataProvider'));
	}
}
