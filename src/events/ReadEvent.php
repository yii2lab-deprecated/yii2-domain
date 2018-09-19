<?php

namespace yii2lab\domain\events;

use yii\base\Event;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Collection;

class ReadEvent extends Event {
	
	const TYPE_ENTITY = 'TYPE_ENTITY';
	const TYPE_COLLECTION = 'TYPE_COLLECTION';
	
	/**
	 * @var BaseEntity|Collection|array
	 */
	public $content;
	public $type;
	
}
