<?php

namespace App\Repositories\Eloquent;

use App\Repositories\CommentRepositoryInterface;
use App\Domain\Entities;
use App\DataAccess\Eloquent;

class CommentRepository implements CommentRepositoryInterface
{
    /** @var Eloquent\Comment  */
    protected Eloquent\Comment $commentEloquent;

    /**
     * @param Eloquent\Comment $commentEloquent
     */
    public function __construct(Eloquent\Comment $commentEloquent)
    {
        $this->commentEloquent = $commentEloquent;
    }

    /**
     * @param int $postId
     * @param string $title
     * @param string $body
     * @return Entities\Comment
     */
    public function create(int $postId, string $title, string $body): Entities\Comment
    {
        $comment = Entities\Comment::create($postId, $title, $body);

        $eloquentComment = $this->commentEloquent->newQuery()->create([
            'post_id' => $comment->getPostId(),
            'title' => $comment->getTitle(),
            'body' => $comment->getBody(),
            'status' => $comment->getStatus(),
        ]);

        return Entities\Comment::reConstruct(
            $eloquentComment->id,
            $eloquentComment->post_id,
            $eloquentComment->title,
            $eloquentComment->body,
            $eloquentComment->status,
            $eloquentComment->created_at,
            $eloquentComment->updated_at
        );
    }
}
