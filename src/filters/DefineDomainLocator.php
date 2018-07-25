<?php

namespace yii2lab\domain\filters;

use common\locators\DomainLocator;
use Yii;
use yii2lab\designPattern\scenario\base\BaseScenario;

use yii2module\lang\domain\helpers\DomainConfigHelper;

class DefineDomainLocator extends BaseScenario {
	
	public function run() {
		$config = $this->getData();
		if(!$this->isHasDomainProperty()) {
			return $config;
		}
		// todo: deprecated ($config['components'])
		$domains = $this->extractDomainsFromComponent($config['components']);
		$this->createDomainLocator($domains);
		$this->setData($config);
		return null;
	}
	
	private function isHasDomainProperty() {
		return property_exists(Yii::class, 'domain');
	}
	
	private function createDomainLocator($domains) {
		Yii::$domain = new DomainLocator();
		Yii::$domain->setComponents($domains);
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
