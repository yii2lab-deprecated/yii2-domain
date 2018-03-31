<?php

namespace yii2lab\domain\base;

use yii2mod\helpers\ArrayHelper;

class FiledbActiveRecord extends \yii2tech\filedb\ActiveRecord {
	
	public function attributes() {
		static $attributes;
		if ($attributes === null) {
			$rows = static::getDb()->readData(static::fileName());
			if(empty($rows)) {
				$schema = static::getDb()->readData('schema' . SL . static::fileName());
				$attributes = ArrayHelper::getColumn($schema, 'name');
			} else {
				$attributes = array_keys(reset($rows));
			}
		}
		return $attributes;
	}
	
}