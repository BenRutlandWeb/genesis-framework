<?php

namespace Genesis\Database\Models;

use Carbon\Carbon;
use Genesis\Database\Models\Attachment;
use Genesis\Database\Models\Comment;
use Genesis\Database\Models\PostMeta;
use Genesis\Database\Models\Taxonomy;
use Genesis\Database\Models\Term;
use Genesis\Database\Models\TermRelationship;
use Genesis\Database\Models\User;
use Genesis\Database\Scopes\PostTypeScope;
use Genesis\Database\Traits\HasMeta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Staudenmeir\EloquentHasManyDeep\HasRelationships as HasManyDeep;

class PostType extends Model
{
    use HasMeta;
    use HasManyDeep;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'post_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'post_modified';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * The post type for the model.
     *
     * @var array|string
     */
    public $postType = '';

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new PostTypeScope);
    }

    /**
     * Get the Post title.
     *
     * @return string
     */
    public function getTitleAttribute(): string
    {
        return $this->post_title;
    }

    /**
     * Get the Post content. Strip HTML comments (from the block editor).
     *
     * @return string
     */
    public function getContentAttribute(): string
    {
        return preg_replace('/<!--.*?-->/', '', $this->post_content);
    }

    /**
     * Get the Post status.
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        return $this->post_status;
    }

    /**
     * Get the Post type.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return $this->post_type;
    }

    /**
     * Get the Post slug.
     *
     * @return string
     */
    public function getSlugAttribute(): string
    {
        return $this->post_name;
    }

    /**
     * Return the post URL.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return get_permalink($this->ID);
    }

    /**
     * Get the created at date.
     *
     * @return Carbon\Carbon
     */
    public function getCreatedAtAttribute(): Carbon
    {
        return $this->post_date;
    }

    /**
     * Get the updated at date.
     *
     * @return Carbon\Carbon
     */
    public function getUpdatedAtAttribute(): Carbon
    {
        return $this->post_modified;
    }

    /**
     * Return the featured image HTML.
     *
     * @param string|array $size
     * @param string|array $attr
     * @return string
     */
    public function featuredImage($size = 'post-thumbnail', $attr = ''): string
    {
        return wp_get_attachment_image($this->getMeta('_thumbnail_id'), $size, false, $attr);
    }

    /**
     * Scope the Posttype query to published posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('post_status', 'publish');
    }

    /**
     * Get the Posttype author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function author(): Relation
    {
        return $this->belongsTo(User::class, 'post_author');
    }

    /**
     * Get the Posttype comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function comments(): Relation
    {
        return $this->hasMany(Comment::class, 'comment_post_ID');
    }

    /**
     * Get the Posttype meta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function meta(): Relation
    {
        return $this->hasMany(PostMeta::class, 'post_id');
    }

    /**
     * Get the Posttype attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function attachments(): Relation
    {
        return $this->hasMany(Attachment::class, 'post_parent');
    }

    /**
     * Get the Posttype terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function terms(): Relation
    {
        return $this->hasManyDeep(
            Term::class,
            [TermRelationship::class, Taxonomy::class],
            [
                'object_id',
                'term_taxonomy_id',
                'term_id',
            ],
            [
                'ID',
                'term_taxonomy_id',
                'term_id',
            ]
        );
    }
}
