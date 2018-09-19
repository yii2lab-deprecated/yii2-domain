<?php

namespace yii2lab\domain\events;

use yii\base\Event;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Collection;

class ReadEvent extends Event {
	
	/**
	 * @var BaseEntity|Collection|array
	 */
	public $content;
	
}
