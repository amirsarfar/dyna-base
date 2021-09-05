<?php

namespace Amirsarfar\DynaBase\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Relation
 *
 * @property string $parent_key
 * @property string $child_key
 * @property int $parent_id
 * @property int $child_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Relation newModelQuery()
 * @method static Builder|Relation newQuery()
 * @method static Builder|Relation query()
 * @method static Builder|Relation whereChildId($value)
 * @method static Builder|Relation whereChildKey($value)
 * @method static Builder|Relation whereCreatedAt($value)
 * @method static Builder|Relation whereParentId($value)
 * @method static Builder|Relation whereParentKey($value)
 * @method static Builder|Relation whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Relation extends Model
{
    use HasFactory;
}
