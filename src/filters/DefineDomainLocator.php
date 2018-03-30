<?php

namespace yii2lab\domain\filters;

use Yii;
use yii\base\BaseObject;
use yii2lab\designPattern\filter\interfaces\FilterInterface;
use yii2module\lang\domain\helpers\DomainConfigHelper;

class DefineDomainLocator extends BaseObject implements FilterInterface {
	
	public function run($config) {
		if(!property_exists(Yii::class, 'domain')) {
			return $config;
		}
		if(!class_exists('DomainContainer')) {
			require VENDOR_DIR . DS . 'yii2lab' . DS . 'yii2-domain' . DS . 'src' . DS . 'yii2' . DS . 'DomainContainer.php';
		}
		$domains = [];
		foreach($config['components'] as $id => $component) {
			if(DomainConfigHelper::isDomain($component)) {
				$domains[$id] = $component;
			}
		}
		Yii::$domain = new \DomainContainer();
		Yii::$domain->setComponents($domains);
		return $config;
	}
}
