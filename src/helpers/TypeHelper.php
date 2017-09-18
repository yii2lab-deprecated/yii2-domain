<?php

namespace yii2lab\domain\helpers;

use yii\helpers\ArrayHelper;

class TypeHelper {
	
	private static $instance;
	
	public static function serialize($item, $formatMap) {
		if(empty($formatMap)) {
			return $item;
		}
		foreach($formatMap as $fieldName => $format) {
			if(is_array($format)) {
				if(isset($item[ $fieldName ])) {
					if(ArrayHelper::isIndexed($item[ $fieldName ])) {
						foreach($item[ $fieldName ] as $kk => $vv) {
							$item[ $fieldName ][ $kk ] = self::serialize($vv, $format);
						}
					} else {
						$item[ $fieldName ] = self::serialize($item[ $fieldName ], $format);
					}
				}
				continue;
			}
			if(!array_key_exists($fieldName, $item)) {
				continue;
			}
			if($format == 'hide') {
				unset($item[ $fieldName ]);
			} elseif($format == 'hideIfNull' && empty($item[ $fieldName ])) {
				unset($item[ $fieldName ]);
			} else {
				$item[ $fieldName ] = self::encode($item[ $fieldName ], $format);
			}
		}
		return $item;
	}
	
	public static function encode($value, $typeStr) {
		$arr = explode(':', $typeStr);
		$param = null;
		if(count($arr) > 1) {
			list($type, $param) = $arr;
		} else {
			list($type) = $arr;
		}
		$instance = self::getInstance();
		$method = 'type' . ucfirst($type);
		if(method_exists($instance, $method)) {
			$value = $instance->$method($value, $param);
		} elseif(function_exists($type)) {
			if(isset($param)) {
				$value = $type($value, $param);
			} else {
				$value = $type($value);
			}
		}
		return $value;
	}
	
	private static function getInstance() {
		if(empty(self::$instance)) {
			self::$instance = new static;
		}
		return self::$instance;
	}
	
	private function typeTime($value, $param) {
		if(empty($value)) {
			return null;
		}
		//return $value;
		/* if(!is_numeric($value)) {
			$value = str_replace(['Z', 'O'], '', $value);
			$value = str_replace('T', ' ', $value);
		}
		$value = Yii::$app->formatter->asDateTime($value);
		return $value; */
		//if($param == 'api') {
		$mask = 'Y-m-d\TH:i:s\Z';
		//} else {
		//	$mask = 'Y-m-d H:i:s';
		//}
		if(is_numeric($value)) {
			$value = date('Y-m-d H:i:s', $value);
		}
		
		$datetime = new \DateTime($value);
		$value = $datetime->format($mask);
		return $value;
	}
	
	private function typeInteger($value, $param) {
		$value = intval($value);
		return $value;
	}
	
	private function typeFloat($value, $param) {
		$value = floatval($value);
		return $value;
	}
	
	private function typeString($value, $param) {
		$value = strval($value);
		return $value;
	}
	
	private function typeBoolean($value, $param) {
		$value = !empty($value);
		return $value;
	}
	
	private function typeNull($value, $param) {
		if(empty($value)) {
			$value = null;
		}
		return $value;
	}
	
}