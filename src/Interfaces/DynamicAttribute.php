<?php

namespace Amirsarfar\DynaBase\Interfaces;

interface DynamicAttribute
{
    public function get(string $key);
    
    public function set(string $key, $value): void;
}