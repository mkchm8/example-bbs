<?php

namespace App\Repositories;

interface CommentRepositoryInterface
{
    /**
     * @param int $postId
     * @param string $title
     * @param string $body
     * @return void
     */
    public function create(int $postId, string $title, string $body): void;
}
