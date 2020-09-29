<?php

namespace Genesis\Database\Models;

use Genesis\Database\Models\Taxonomy;
use Genesis\Database\Models\TermMeta;
use Genesis\Database\Models\TermRelationship;
use Genesis\Database\Models\Post;
use Genesis\Database\Traits\HasMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Staudenmeir\EloquentHasManyDeep\HasRelationships as HasManyDeep;

class Term extends Model
{
    use HasMeta;
    use HasManyDeep;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'terms';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'term_id';

    /**
     * Get the User meta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function meta(): Relation
    {
        return $this->hasMany(TermMeta::class, 'term_id');
    }

    /**
     * Get the Term posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function posts(): Relation
    {
        return $this->hasManyDeep(
            Post::class,
            [Taxonomy::class, TermRelationship::class],
            [
                'term_id',
                'term_taxonomy_id',
                'ID',
            ],
            [
                'term_id',
                'term_taxonomy_id',
                'object_id',
            ]
        );
    }
}
