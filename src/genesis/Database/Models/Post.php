<?php

namespace Genesis\Database\Models;

use Genesis\Database\Models\PostType;
use Genesis\Database\Traits\HasMeta;
use Illuminate\Database\Eloquent\Relations\Relation;
use Staudenmeir\EloquentHasManyDeep\HasRelationships as HasManyDeep;

class Post extends PostType
{
    use HasMeta;
    use HasManyDeep;

    /**
     * The post type for the model.
     *
     * @var array|string
     */
    public $postType = 'post';

    /**
     * Get the Post categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function categories(): Relation
    {
        return $this->terms()->where('taxonomy', 'category');
    }

    /**
     * Get the Post tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function tags(): Relation
    {
        return $this->terms()->where('taxonomy', 'post_tag');
    }
}
