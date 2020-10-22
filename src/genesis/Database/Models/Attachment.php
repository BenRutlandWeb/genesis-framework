<?php

namespace Genesis\Database\Models;

use Genesis\Database\Models\PostType;
use Illuminate\Database\Eloquent\Relations\Relation;

class Attachment extends PostType
{
    /**
     * The post type for the model.
     *
     * @var array|string
     */
    public $postType = 'attachment';

    /**
     * Get the Attachment parent post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function post(): Relation
    {
        return $this->belongsTo(PostType::class, 'post_parent');
    }
}
