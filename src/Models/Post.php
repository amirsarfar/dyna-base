<?php

namespace Amirsarfar\DynaBase\Models;

use Amirsarfar\DynaBase\Traits\HasDynamicAttributes;
use Amirsarfar\DynaBase\Traits\HasDynoRelations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property int $type_id
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Post[] $children
 * @property-read int|null $children_count
 * @property-read Collection|LargeMeta[] $large_metas
 * @property-read int|null $large_metas_count
 * @property-read Collection|Meta[] $metas
 * @property-read int|null $metas_count
 * @property-read Collection|Post[] $parents
 * @property-read int|null $parents_count
 * @property-read Type $type
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post query()
 * @method static Builder|Post whereCreatedAt($value)
 * @method static Builder|Post whereDeletedAt($value)
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereTypeId($value)
 * @method static Builder|Post whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Post extends Model
{
    use HasFactory, HasDynamicAttributes, HasDynoRelations, SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::retrieved(function ($post) {
            $post->setupDynamicAttributes();
        });
        
        static::created(function ($post) {
            $post->setupDynamicAttributes();
        });
    }

    public function setupDynamicAttributes()
    {
        $attributes = $this->type->definition;
        foreach ($attributes as $attribute) {
            $this->registerDynamicAttribute(
                generateAttributeName($attribute),
                generateAttributeGetter($attribute),
                generateAttributeSetter($attribute),
            );
            //TODO should make these attributes load from database once and be saved to model object
            $this->append(generateAttributeName($attribute));
        }
    }

    public function getJsonRepAttribute()
    {
        $data = [];
        $data['id'] = $this->id;
        $attributes = $this->type->definition;
        foreach ($attributes as $attribute) {
            if($attribute->type != 'relation') {
                $attribute_name = generateAttributeName($attribute);
                $data[$attribute_name] = $this->$attribute_name;
            }
        }
        return $data;
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function metas(): HasMany
    {
        return $this->hasMany(Meta::class);
    }

    public function large_metas(): HasMany
    {
        return $this->hasMany(LargeMeta::class);
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, Relation::class, 'parent_id', 'child_id');
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, Relation::class, 'child_id', 'parent_id');
    }
}
