<?php

namespace App\DataAccess\Eloquent;

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
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
