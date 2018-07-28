<?php

namespace yii2lab\domain\filters;

use common\locators\DomainLocator;
use Dii;
use Yii;
use yii2lab\designPattern\scenario\base\BaseScenario;
use yii2lab\designPattern\scenario\helpers\ScenarioHelper;
use yii2lab\domain\helpers\DomainLangHelper;

class DefineDomainLocator extends BaseScenario {
	
	public $filters = [];
	
	public function run() {
		/*$config = $this->getData();
		$domains = $config['params']['domains'];
		unset($config['params']['domains']);*/
		$domains = $this->loadConfig();
		//prr($domains,1,1);
		$this->createDomainLocator($domains);
		//$config = DomainLangHelper::setDomainTranslationConfig($config, $domains);
		//$this->setData($config);
	}
	
	private function loadConfig() {
		$loaders = ScenarioHelper::forgeCollection($this->filters);
		$domains = ScenarioHelper::runAll($loaders, []);
		return $domains;
	}
	
	private function loadDomainContainerClass() {
		if(!class_exists(Dii::class)) {
			require VENDOR_DIR . DS . 'yii2lab' . DS . 'yii2-domain' . DS . 'src' . DS . 'yii2' . DS . 'Dii.php';
		}
	}
	
	private function createDomainLocator($domains) {
		$this->loadDomainContainerClass();
		Dii::$domain = new DomainLocator;
		Dii::$domain->setComponents($domains);
		Yii::$domain = Dii::$domain;
	}
	
}
