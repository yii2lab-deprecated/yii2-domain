<?php

namespace yii2lab\domain\interfaces;

interface ValueObjectInterface {

    public function set($value);
    public function get($default = null);
    public function has();
    public function encode($value);
    public function decode($value);
    public function getDefault();
    public function isValid($value);

}
