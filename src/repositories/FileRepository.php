<?php

namespace yii2lab\domain\repositories;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

abstract class FileRepository extends BaseRepository {
	
	public $pathName;
	public $format;
	
	private $isDirectoryExists = false;
	
	protected function saveFile($name, $data, $format = null) {
		$fileName = $this->getFileName($name, $format);
		file_put_contents($fileName, $data);
	}
	
	protected function loadFile($name, $format = null) {
		$fileName = $this->getFileName($name, $format);
		return file_get_contents($fileName);
	}
	
	/*protected function getUrl($name, $format = null) {
		$path = env('servers.static.domain') . $this->getUri($name, $format);
		return $path;
	}*/

	protected function getUri($name, $format = null) {
		$path = param('static.path.' . $this->pathName) . '/';
		$fileName = $path . $this->getFileName($name, $format);
		return $fileName;
	}
	
	protected function getFilePath($name, $format = null) {
		$path = $this->getPath();
		$fileName = $path . $this->getFileName($name, $format);
		return $fileName;
	}
	
	protected function getFileName($name, $format = null) {
		$format = $this->getFormat($format);
		$fileName = $name . '.' . $format;
		return $fileName;
	}
	
	protected function getFormat($format = null) {
		if(empty($this->format)) {
			throw new InvalidConfigException('Property "format" not assigned');
		}
		$format = !empty($format) ? $format : $this->format;
		return $format;
	}

	protected function getDirectory($addPath = null) {
		if(empty($this->pathName)) {
			throw new InvalidConfigException('Property "pathName" not assigned');
		}
		$path = param('static.path.' . $this->pathName);
		$basePath = env('servers.static.publicPath');
		$path = Yii::getAlias($basePath . '/' . $path);
		$path = FileHelper::normalizePath($path);
		if($addPath) {
			$path .= DS . $addPath;
		}
		return $path;
	}

	protected function getPath($addPath = null) {
		$directory = $this->getDirectory($addPath);
		return $directory . DS;
	}
	
	protected function createDirectory($addPath = null) {
		if(isset($this->isDirectoryExists[$addPath])) {
			return;
		}
		$dir = $this->getPath($addPath);
		if(!is_dir($dir)) {
			FileHelper::createDirectory($dir);
		}
		$this->isDirectoryExists[$addPath] = true;
	}
	
}