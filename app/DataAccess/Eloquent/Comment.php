<?php

namespace App\DataAccess\Eloquent;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends EloquentModel
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['post_id', 'title', 'body', 'status'];

    /**
     * @param Builder $builder
     * @param int $status
     *
     * @return Builder
     */
    public function scopeFilterStatus(Builder $builder, int $status): Builder
    {
        return $builder->where('status', $status);
    }

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return CommentFactory
     */
    protected static function newFactory(): CommentFactory
    {
        return CommentFactory::new();
    }
}
