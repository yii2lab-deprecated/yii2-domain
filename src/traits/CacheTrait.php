<?php

namespace yii2lab\domain\traits;

use Closure;
use Yii;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii2mod\helpers\ArrayHelper;

trait CacheTrait {
	
	/**
	 * @var Cache
	 */
	private $cacheInstance;
	
	public function getFromCache($uri, $data = [], $headers = []) {
		$callback = function() use($uri, $data, $headers) {
			return parent::get($uri, $data, $headers)->data;
		};
		$collection = $this->runCache($callback, $uri);
		return $collection;
	}
	
	public function clearCache($uri) {
		$cacheInstance = $this->getCacheInstance();
		$cacheInstance->delete($uri);
	}
	
	public function reCache($uri, $data = [], $headers = []) {
		$this->clearCache($uri);
		$collection = $this->getFromCache($uri, $data, $headers);
		return $collection;
	}

	public function runCache($callback, $name = null) {
		if(empty($this->cache['keyPrefix'])) {
			$collection = call_user_func($callback);
			return $collection;
		}
		$cacheInstance = $this->getCacheInstance();
		if($cacheInstance->exists($name)) {
			$collection = $cacheInstance->get($name);
		} else {
			$collection = call_user_func($callback);
			$cacheInstance->set($name, $collection);
		}
		return $collection;
	}

	/**
	 * @return Cache
	 * @throws InvalidConfigException
	 */
	public function getCacheInstance() {
		if(!isset($this->cacheInstance)) {
			$config = config('components.cache');
			$config = ArrayHelper::merge($config, $this->cache);
			if(empty($config['keyPrefix'])) {
				throw new InvalidConfigException('Empty keyPrefix');
			}
			$this->cacheInstance = Yii::createObject($config);
		}
		return $this->cacheInstance;
	}

}