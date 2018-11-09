<?php

namespace yii2lab\domain\filters;

use Dii;
use Yii;
use App;
use yii2lab\app\domain\helpers\CacheHelper;
use yii2lab\domain\base\BaseDomainLocator;
use yii2lab\extension\scenario\base\BaseScenario;
use yii2lab\extension\scenario\helpers\ScenarioHelper;

class DefineDomainLocator extends BaseScenario
{
	
	public $filters = [];
	
	public function run()
	{
		/*$config = $this->getData();
		$domains = $config['params']['domains'];
		unset($config['params']['domains']);*/
		$domains = $this->loadConfig();
		//prr($domains,1,1);
		$this->createDomainLocator($domains);
		//$config = DomainLangHelper::setDomainTranslationConfig($config, $domains);
		//$this->setData($config);
	}
	
	private function loadConfig()
	{
		$definition = '';
		$callback = function () use ($definition) {
			$loaders = ScenarioHelper::forgeCollection($this->filters);
			$domains = ScenarioHelper::runAll($loaders, []);
			return $domains;
		};
		$config = CacheHelper::forge(APP . '_domain_config', $callback);
		return $config;
	}
	
	private function loadDomainContainerClass()
	{
		if(!class_exists(Dii::class)) {
			require VENDOR_DIR . DS . 'yii2lab' . DS . 'yii2-domain' . DS . 'src' . DS . 'yii2' . DS . 'Dii.php';
		}
	}
	
	private function createDomainLocator($domains)
	{
		$this->loadDomainContainerClass();
		$domain = new BaseDomainLocator;
		/*if(class_exists(DomainLocator::class)) {
			$domain = new DomainLocator;
		} else {
			$domain = new BaseDomainLocator;
		}*/
        $domain->setComponents($domains);
        App::$domain = $domain;
		Dii::$domain = $domain;
		if(property_exists(Yii::class, 'domain')) {
            Yii::$domain = $domain;
        }
	}
	
}
