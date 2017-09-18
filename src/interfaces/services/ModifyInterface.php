<?php

namespace common\ddd\interfaces\services;

interface ModifyInterface {
	
	public function create($data);
	
	public function updateById($id, $data);
	
	public function deleteById($id);
	
}