<?php

namespace yii2lab\domain\helpers\factory;

use yii2lab\domain\Domain;

class RepositoryFactoryHelper extends BaseFactoryHelper {
	
	protected static function genClassName($id, $definition, Domain $domain) {
		$driver = FactoryHelper::getDriverFromConfig($definition, $domain->defaultDriver);
		$class = 'repositories\\' . $driver . '\\' . ucfirst($id) . 'Repository';
		return $class;
	}
	
}
