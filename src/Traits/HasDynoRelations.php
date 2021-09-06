<?php

namespace Amirsarfar\DynaBase\Traits;

use Amirsarfar\DynaBase\Models\Post;
use Illuminate\Support\Collection;

trait HasDynoRelations
{
    public function removeChild($removable)
    {
        if ($removable === null) return;
        $removable_id = $removable instanceof Post ? $removable->id : $removable;
        return $this->children()->detach($removable_id);
    }

    public function removeParent($removable)
    {
        if ($removable === null) return;
        $removable_id = $removable instanceof Post ? $removable->id : $removable;
        return $this->parents()->detach($removable_id);
    }

    public function addChild($addable, $child_key, $parent_key)
    {
        if ($addable === null) return;
        $addable_id = $addable instanceof Post ? $addable->id : $addable;
        return $this->children()->attach($addable_id, compact('parent_key', 'child_key'));
    }

    public function addParent($addable, $parent_key, $child_key)
    {
        if ($addable === null) return;
        $addable_id = $addable instanceof Post ? $addable->id : $addable;
        return $this->parents()->attach($addable_id, compact('parent_key', 'child_key'));
    }

    /* -------------------------------- */

    public function removeChildren($removables, $child_key, $parent_key): int
    {
        $removable_ids = $removables instanceof Collection ? $removables->pluck('id')->all() : $removables;
        $removable_ids = array_intersect($removable_ids, $this->children()->wherePivot('parent_key', $parent_key)->wherePivot('child_key', $child_key)->get()->pluck('id')->all());
        return $this->children()->detach($removable_ids);
    }

    public function removeParents($removables, $parent_key, $child_key): int
    {
        $removable_ids = $removables instanceof Collection ? $removables->pluck('id')->all() : $removables;
        $removable_ids = array_intersect($removable_ids, $this->parents()->wherePivot('parent_key', $parent_key)->wherePivot('child_key', $child_key)->get()->pluck('id')->all());
        return $this->parents()->detach($removable_ids);

    }

    public function addChildren($addables, $child_key, $parent_key)
    {
        $addable_ids = $addables instanceof Collection ? $addables->pluck('id')->all() : $addables;
        $addable_ids = array_diff($addable_ids, $this->children()->wherePivot('parent_key', $parent_key)->wherePivot('child_key', $child_key)->get()->pluck('id')->all());
        return $this->children()->attach($addable_ids, compact('parent_key', 'child_key'));
    }

    public function addParents($addables, $parent_key, $child_key)
    {
        $addable_ids = $addables instanceof Collection ? $addables->pluck('id')->all() : $addables;
        $addable_ids = array_diff($addable_ids, $this->parents()->wherePivot('parent_key', $parent_key)->wherePivot('child_key', $child_key)->get()->pluck('id')->all());
        return $this->parents()->attach($addable_ids, compact('parent_key', 'child_key'));
    }

    /* -------------------------------- */

    public function syncChildren($syncables, $child_key, $parent_key): array
    {
        $syncable_ids = $syncables instanceof Collection ? $syncables->pluck('id')->all() : $syncables;
        return $this->children()->syncWithPivotValues($syncable_ids, compact('parent_key', 'child_key'));
    }

    public function syncParents($syncables, $parent_key, $child_key): array
    {
        $syncable_ids = $syncables instanceof Collection ? $syncables->pluck('id')->all() : $syncables;
        return $this->parents()->syncWithPivotValues($syncable_ids, compact('parent_key', 'child_key'));
    }

    /* -------------------------------- */

    public function hasChild($child): bool
    {
        $child_id = $child instanceof Post ? $child->id : $child;
        return $this->children()->where('id', $child_id)->exists();
    }

    public function hasParent($parent): bool
    {
        $parent_id = $parent instanceof Post ? $parent->id : $parent;
        return $this->parents()->where('id', $parent_id)->exists();
    }
}
