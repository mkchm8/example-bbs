<?php

namespace App\Repositories;

use App\Domain\Entities\Comment;

interface CommentRepositoryInterface
{
    /**
     * @param int $postId
     * @param string $title
     * @param string $body
     * @return Comment
     */
    public function create(int $postId, string $title, string $body): Comment;
}
