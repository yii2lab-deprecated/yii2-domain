<?php

namespace yii2lab\domain\repositories;

use creocoder\flysystem\Filesystem;
use Yii;

class StaticServerRepository extends BaseRepository {
	
	public $pathName = '';
	
	/**
	 * @var Filesystem
	 */
	private $storeInstance;
	
	protected function writeFile($fileName, $content) {
		$this->removeFile($fileName);
		$staticFs = $this->storeInstance();
		$file = $this->fullName($fileName);
		$staticFs->write($file, $content);
	}
	
	protected function removeFile($fileName) {
		$staticFs = $this->storeInstance();
		$file = $this->fullName($fileName);
		if($staticFs->has($file)) {
			$staticFs->delete($file);
		}
	}
	
	private function fullName($name) {
		$file = $this->pathName . SL . $name;
		$file = str_replace(BSL, SL, $file);
		return $file;
	}
	
	private function storeInstance() {
		if(!$this->storeInstance instanceof Filesystem) {
			$this->initStoreInstance();
		}
		return $this->storeInstance;
	}
	
	private function initStoreInstance() {
		$definition = env('servers.static.connection');
		$driver = env('servers.static.driver');
		$driver = ucfirst($driver);
		$definition['class'] = 'creocoder\flysystem\\' . $driver . 'Filesystem';
		$this->storeInstance = Yii::createObject($definition);
		return $this->storeInstance;
	}
	
}