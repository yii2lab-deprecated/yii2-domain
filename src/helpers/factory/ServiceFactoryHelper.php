<?php

namespace yii2lab\domain\helpers\factory;

use yii2lab\domain\Domain;

class ServiceFactoryHelper extends BaseFactoryHelper {
	
	protected static function genClassName($id, $definition, Domain $domain) {
		$class = 'services\\';
		if(!empty($definition) && is_string($definition)) {
			$class .= $definition . '\\';
		}
		$class .=  ucfirst($id) . 'Service';
		return $class;
	}
	
}
