<?php

namespace Amirsarfar\DynaBase\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\LargeMeta
 *
 * @property int $id
 * @property int $post_id
 * @property string $key
 * @property string $value
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|LargeMeta newModelQuery()
 * @method static Builder|LargeMeta newQuery()
 * @method static Builder|LargeMeta query()
 * @method static Builder|LargeMeta whereCreatedAt($value)
 * @method static Builder|LargeMeta whereDeletedAt($value)
 * @method static Builder|LargeMeta whereId($value)
 * @method static Builder|LargeMeta whereKey($value)
 * @method static Builder|LargeMeta wherePostId($value)
 * @method static Builder|LargeMeta whereUpdatedAt($value)
 * @method static Builder|LargeMeta whereValue($value)
 * @mixin Eloquent
 */
class LargeMeta extends Model
{
    use HasFactory;
    
    protected $guarded = [];
}
