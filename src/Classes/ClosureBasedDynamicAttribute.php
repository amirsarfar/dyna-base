<?php

namespace Amirsarfar\DynaBase\Classes;

use Amirsarfar\DynaBase\Interfaces\DynamicAttribute;
use Closure;

class ClosureBasedDynamicAttribute implements DynamicAttribute
{
    protected $getter;
    
    protected $setter;
    
    public function __construct(Closure $getter, Closure $setter)
    {
        $this->getter = $getter;
        $this->setter = $setter;
    }
    
    public function get(string $key)
    {
        return ($this->getter)($key);
    }
    
    public function set(string $key, $value): void
    {
        ($this->setter)($key, $value);
    }
}
