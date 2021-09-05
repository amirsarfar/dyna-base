<?php

namespace Amirsarfar\DynaBase\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Type
 *
 * @property int $id
 * @property string $key
 * @property string $title
 * @property iterable $definition
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Post[] $posts
 * @property-read int|null $posts_count
 * @method static Builder|Type newModelQuery()
 * @method static Builder|Type newQuery()
 * @method static Builder|Type query()
 * @method static Builder|Type whereCreatedAt($value)
 * @method static Builder|Type whereDefinition($value)
 * @method static Builder|Type whereDeletedAt($value)
 * @method static Builder|Type whereId($value)
 * @method static Builder|Type whereKey($value)
 * @method static Builder|Type whereTitle($value)
 * @method static Builder|Type whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Type extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getDefinitionAttribute($value)
    {
        return json_decode($value);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function getRelatedTypeByName($related_name)
    {
        return Type::where('key', $this->getRelatedAttributeByName($related_name)->options->type_key)->first();
    }

    public function getRelatedAttributeByName($related_name)
    {
        return collect($this->definition)->where('name', $related_name)->first();
    }

}
