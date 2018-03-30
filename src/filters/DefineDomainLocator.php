<?php

namespace yii2lab\domain\filters;

use Yii;
use yii\base\BaseObject;
use yii2lab\designPattern\filter\interfaces\FilterInterface;
use yii2lab\domain\yii2\DomainContainer;
use yii2module\lang\domain\helpers\DomainConfigHelper;

class DefineDomainLocator extends BaseObject implements FilterInterface {
	
	public function run($config) {
		if(!$this->isHasDomainProperty()) {
			return $config;
		}
		$this->loadDomainContainerClass();
		$domains = $this->extractDomainsFromComponent($config['components']);
		$this->createDomainLocator($domains);
		return $config;
	}
	
	private function isHasDomainProperty() {
		return property_exists(Yii::class, 'domain');
	}
	
	private function loadDomainContainerClass() {
		if(!class_exists(DomainContainer::class)) {
			require VENDOR_DIR . DS . 'yii2lab' . DS . 'yii2-domain' . DS . 'src' . DS . 'yii2' . DS . 'DomainContainer.php';
		}
	}
	
	private function createDomainLocator($domains) {
		Yii::$domain = new DomainContainer();
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
