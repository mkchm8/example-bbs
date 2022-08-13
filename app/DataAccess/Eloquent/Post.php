<?php

namespace App\DataAccess\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends EloquentModel
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['status'];

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
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
