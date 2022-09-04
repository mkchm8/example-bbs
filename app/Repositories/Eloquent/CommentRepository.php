<?php

namespace App\Repositories\Eloquent;

use App\Repositories\CommentRepositoryInterface;
use App\Domain\Entities;
use App\DataAccess\Eloquent;

class CommentRepository implements CommentRepositoryInterface
{
    protected Eloquent\Comment $commentEloquent;

    public function __construct(Eloquent\Comment $commentEloquent)
    {
        $this->commentEloquent = $commentEloquent;
    }

    /**
     * TODO: Commentエンティティを返すようにする
     * @param int $postId
     * @param string $title
     * @param string $body
     * @return void
     */
    public function create(int $postId, string $title, string $body): void
    {
        $comment = Entities\Comment::create($postId, $title, $body);

        $this->commentEloquent->newQuery()->create([
            'post_id' => $comment->getPostId(),
            'title' => $comment->getTitle(),
            'body' => $comment->getBody(),
            'status' => $comment->getStatus(),
        ]);
    }
}
