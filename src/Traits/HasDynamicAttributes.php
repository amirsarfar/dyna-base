<?php

namespace Amirsarfar\DynaBase\Traits;

use Amirsarfar\DynaBase\Classes\ClosureBasedDynamicAttribute;
use Amirsarfar\DynaBase\Interfaces\DynamicAttribute;
use Closure;

trait HasDynamicAttributes
{
    protected $dynamicAttributes = [];
    
    public function registerDynamicAttribute(string $key, Closure $getter, Closure $setter)
    {
        $this->registerDynamicAttributeClass($key, new ClosureBasedDynamicAttribute(
            $getter->bindTo($this),
            $setter->bindTo($this)
        ));
    }

    public function registerDynamicAttributeClass(string $key, DynamicAttribute $dynamicAttribute)
    {
        $this->dynamicAttributes[$key] = $dynamicAttribute;
    }

    public function getAttribute($key)
    {
        if ($da = $this->getDynamicAttributeClass($key)) {
            return $da->get($key);
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if ($da = $this->getDynamicAttributeClass($key)) {
            $da->set($key, $value);

            return $this;
        }

        return parent::setAttribute($key, $value);
    }

    protected function getDynamicAttributeClass(string $key): ?DynamicAttribute
    {
        return $this->dynamicAttributes[$key] ?? null;
    }
}
