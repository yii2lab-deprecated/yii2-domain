<?php

namespace yii2lab\domain\repositories;

use Yii;
use yii2lab\extension\store\Store;

/**
 * Class DiscRepository
 *
 * @package yii2lab\domain\repositories
 *
 * @deprecated use class \yii2lab\extension\filedb\repositories\base\BaseFiledbRepository
 */
abstract class DiscRepository extends BaseRepository {

	public $table;
	public $format = 'php';
	public $path = '@common/data';
	public $readonly = false;
	private $collection;
	private $store;

	protected function getCollection() {
		if(!isset($this->collection)) {
			$this->loadCollection();
		}
		return $this->collection;
	}

	protected function setCollection(Array $collection) {
		$this->collection = $collection;
		$this->saveCollection();
	}

	private function loadCollection() {
		$fileName = $this->getFileName();
		$store = $this->getStore();
		$data = $store->load($fileName);
		if(!empty($data)) {
			$this->collection = $this->alias->decode($data);
		}
		if(!is_array($this->collection) || empty($this->collection)) {
			$this->collection = [];
		}
	}

	private function saveCollection() {
		if($this->readonly) {
			return;
		}
		$data = $this->alias->encode($this->collection);
		$data = array_values($data);
		$fileName = $this->getFileName();
		$store = $this->getStore();
		$store->save($fileName, $data);
	}

	private function getDbDir() {
		return Yii::getAlias($this->path);
	}

	private function getFileName() {
		$dir = $this->getDbDir();
		return $dir . DS . $this->table . DOT . $this->format;
	}

	private function getStore() {
		if(!isset($this->store)) {
			$this->store = new Store($this->format);
		}
		return $this->store;
	}

}