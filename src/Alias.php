<?php

namespace yii2lab\domain;

use yii\helpers\ArrayHelper;

class Alias {
	
	private $aliases = [];
	
	public function setAliases($aliases) {
		$this->aliases = $aliases;
	}
	
	/**
	 * @param $name
	 *
	 * @return array|string
	 */
	public function encode($name) {
		$aliases = $this->aliases;
		if(empty($aliases)) {
			return $name;
		}
		return $this->aggregate($name, $aliases);
	}
	
	public function decode($name) {
		$aliases = $this->aliases;
		if(empty($aliases)) {
			return $name;
		}
		$aliases = array_flip($aliases);
		return $this->aggregate($name, $aliases);
	}
	
	private function aggregate($name, $aliases) {
		if(is_object($name)) {
			return $name;
		}
		if(is_array($name)) {
			$isIndexed = ArrayHelper::isIndexed($name);
			$result = [];
			if($isIndexed) {
				foreach($name as $item) {
					$result[] = $this->aggregate($item, $aliases);
				}
			} else {
				foreach($name as $key => $value) {
					$key = $this->aggregate($key, $aliases);
					$result[ $key ] = $value;
				}
			}
			return $result;
		}
		if(isset($aliases[ $name ])) {
			return $aliases[ $name ];
		}
		return $name;
	}
	
}