<?php

namespace yii2lab\domain\filters;

use common\locators\DomainLocator;
use Dii;
use Yii;
use yii2lab\app\domain\helpers\CacheHelper;
use yii2lab\extension\scenario\base\BaseScenario;
use yii2lab\extension\scenario\helpers\ScenarioHelper;
use yii2lab\domain\base\BaseDomainLocator;
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
        $callback = function () use ($definition) {
            $loaders = ScenarioHelper::forgeCollection($this->filters);
            $domains = ScenarioHelper::runAll($loaders, []);
            return $domains;
        };
        $config = CacheHelper::forge(APP . '_domain_config', $callback);
        return $config;
	}
	
	private function loadDomainContainerClass() {
		if(!class_exists(Dii::class)) {
			require VENDOR_DIR . DS . 'yii2lab' . DS . 'yii2-domain' . DS . 'src' . DS . 'yii2' . DS . 'Dii.php';
		}
	}
	
	private function createDomainLocator($domains) {
		$this->loadDomainContainerClass();
		if(class_exists(DomainLocator::class)) {
            Dii::$domain = new DomainLocator;
        } else {
            Dii::$domain = new BaseDomainLocator;
        }
		Dii::$domain->setComponents($domains);
		Yii::$domain = Dii::$domain;
	}
	
}
