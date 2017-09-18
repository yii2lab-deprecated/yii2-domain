<?php

namespace yii2lab\domain\interfaces\services;

interface ModifyInterface {
	
	public function create($data);
	
	public function updateById($id, $data);
	
	public function deleteById($id);
	
}