<?php

function generateAttributeName($attribute)
{
    return $attribute->name;
}

function generateAttributeGetter($attribute)
{
    //* it will call a function in this file named like generateTextAttributeGetter or generateRelationAttributeGetter and then that function either returns the callback needed or calls another function to return the callback

    $generatorFunction = 'generate' . Str::ucfirst($attribute->type) . 'AttributeGetter';
    return $generatorFunction($attribute);
}

function generateAttributeSetter($attribute)
{
    $generatorFunction = 'generate' . Str::ucfirst($attribute->type) . 'AttributeSetter';
    return $generatorFunction($attribute);
}


//* to get rid of switch statements we can use these dynamically called functions and make the generateAttributeGetter function of this file smaller we then can write functions to do things based on options in attribute of a post type instead of writing a big switch/case

function generateTextAttributeGetter($attribute): Closure
{
    return function ($key) {
        return $this->metas()->where('key', $key)->first()->value ?? '';
    };
}

function generateTextAttributeSetter($attribute): Closure
{
    return function ($key, $value) {
        return $this->metas()->updateOrCreate(['key' => $key], ['value' => $value]);
    };
}

function generateRelationAttributeGetter($attribute)
{
    $generatorFunction = 'generate' . ($attribute->options->type_id == 0 ? 'External' : 'Internal') . ($attribute->options->count == 'many' ? ('Many' . Str::ucfirst(Str::plural($attribute->options->relation))) : ('One' . Str::ucfirst($attribute->options->relation))) . 'RelationAttributeGetter';

    return $generatorFunction($attribute);
}

function generateRelationAttributeSetter($attribute)
{
    return function($key, $value) {
        return new Exception('Relation Attributes Can Not Be Set !');
    };
}

function generateInternalManyChildrenRelationAttributeGetter($attribute): Closure
{
    return function ($key) {
        return $this->children()->wherePivot('parent_key', $key);
    };
}

function generateInternalOneChildRelationAttributeGetter($attribute): Closure
{
    return function ($key) {
        return $this->children()->wherePivot('parent_key', $key)->limit(1);
    };
}

function generateInternalManyParentsRelationAttributeGetter($attribute): Closure
{
    return function ($key) {
        return $this->parents()->wherePivot('child_key', $key);
    };
}

function generateInternalOneParentRelationAttributeGetter($attribute): Closure
{
    return function ($key) {
        return $this->parents()->wherePivot('child_key', $key)->limit(1);
    };
}

    // External relations
    // Dont know how to save them :)

function generateExternalManyChildrenRelationAttributeGetter($attribute): Closure
{
    return function ($key) use ($attribute){
        $related_namescapse = $attribute->options->type_key;
        $classi = get_class(new $related_namescapse());
        return $this->belongsToMany($classi, \App\Models\Meta::class, 'post_id', 'value')->wherePivot('key', $key)->limit(1);
    };
}

function generateExternalOneChildRelationAttributeGetter($attribute): Closure
{
    return function ($key) use ($attribute){
        $related_namescapse = $attribute->options->type_key;
        $classi = get_class(new $related_namescapse());
        return $this->belongsToMany($classi, \App\Models\Meta::class, 'post_id', 'value')->wherePivot('key', $key)->limit(1);
    };
}

function generateExternalManyParentsRelationAttributeGetter($attribute): Closure
{
    return function ($key) use ($attribute){
        $related_namescapse = $attribute->options->type_key;
        $classi = get_class(new $related_namescapse());
        return $this->belongsToMany($classi, \App\Models\Meta::class, 'post_id', 'value')->wherePivot('key', $key);
    };
}

function generateExternalOneParentRelationAttributeGetter($attribute): Closure
{
    return function ($key) use ($attribute){
        $related_namescapse = $attribute->options->type_key;
        $classi = get_class(new $related_namescapse());
        return $this->belongsToMany($classi, \App\Models\Meta::class, 'post_id', 'value')->wherePivot('key', $key)->limit(1);
    };
}