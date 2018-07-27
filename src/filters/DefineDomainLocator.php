<?php

namespace yii2lab\domain\filters;

use common\locators\DomainLocator;
use Dii;
use Yii;
use yii2lab\designPattern\scenario\base\BaseScenario;

use yii2lab\designPattern\scenario\helpers\ScenarioHelper;
use yii2module\lang\domain\helpers\DomainConfigHelper;

class DefineDomainLocator extends BaseScenario {
	
	public function run() {
		$config = $this->getData();
		$config = ScenarioHelper::run(SetDomainTranslationConfig::class, $config);
		if(!$this->isHasDomainProperty()) {
			return $config;
		}
		$this->loadDomainContainerClass();
		// todo: deprecated ($config['components'])
		$domains = $this->extractDomainsFromComponent($config['components']);
		$this->createDomainLocator($domains);
		$this->setData($config);
		return null;
	}
	
	private function isHasDomainProperty() {
		return property_exists(Yii::class, 'domain');
	}
	
	private function loadDomainContainerClass() {
		if(!class_exists(Dii::class)) {
			require VENDOR_DIR . DS . 'yii2lab' . DS . 'yii2-domain' . DS . 'src' . DS . 'yii2' . DS . 'Dii.php';
		}
	}
	private function createDomainLocator($domains) {
		Dii::$domain = new DomainLocator;
		Dii::$domain->setComponents($domains);
		Yii::$domain = Dii::$domain;
	}
	
	private function extractDomainsFromComponent($components) {
		$domains = [];
		foreach($components as $id => $component) {
			if(DomainConfigHelper::isDomain($component)) {
				$domains[$id] = $component;
			}
		}
		return $domains;
	}
}
