<?php

namespace Amirsarfar\DynaBase\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Meta
 *
 * @property int $id
 * @property int $post_id
 * @property string $key
 * @property string $value
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Meta newModelQuery()
 * @method static Builder|Meta newQuery()
 * @method static Builder|Meta query()
 * @method static Builder|Meta whereCreatedAt($value)
 * @method static Builder|Meta whereDeletedAt($value)
 * @method static Builder|Meta whereId($value)
 * @method static Builder|Meta whereKey($value)
 * @method static Builder|Meta wherePostId($value)
 * @method static Builder|Meta whereUpdatedAt($value)
 * @method static Builder|Meta whereValue($value)
 * @mixin Eloquent
 */
class Meta extends Model
{
    use HasFactory;

    protected $guarded = [];
}
