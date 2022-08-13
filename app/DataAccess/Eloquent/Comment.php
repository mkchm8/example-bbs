<?php

namespace App\DataAccess\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends EloquentModel
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['title', 'body'];

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
}
