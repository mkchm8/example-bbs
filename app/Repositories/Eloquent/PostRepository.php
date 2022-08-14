<?php

namespace App\Repositories\Eloquent;

use App\DataAccess\Eloquent\Comment;
use App\DataAccess\Eloquent\Post;
use App\Domain\Entities;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Support\Collection;

class PostRepository implements PostRepositoryInterface
{
    protected Post $post;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * 投稿リストをコメント付きで取得する
     *
     * @param array $conditions
     * @return Collection
     */
    public function findListWithComments(array $conditions): Collection
    {
        $posts = $this->post::with([
            'comments' => function ($query) use ($conditions) {
                $query->filterStatus($conditions['commentStatus']);
            }])
            ->filterStatus($conditions['postStatus'])
            ->get();

        $postEntityCollection = collect();
        foreach ($posts as $post) {
            $comments = $post->comments;

            $commentEntityCollection = collect();
            foreach ($comments as $comment) {
                $this->toCommentDomainEntity($comment, $commentEntityCollection);
            }

            $this->toPostDomainEntity($post, $postEntityCollection, $commentEntityCollection);
        }

        return $postEntityCollection;
    }

    /**
     * 投稿をコメント付きで取得する
     *
     * @param int $postId
     * @return Entities\Post
     */
    public function findByIdWithComments(int $postId): Entities\Post
    {
        $post = $this->post::with(['comments'])->get()->find($postId);
        $comments = $post->comments;

        $postEntityCollection = collect();
        $commentEntityCollection = collect();
        foreach ($comments as $comment) {
            $this->toCommentDomainEntity($comment, $commentEntityCollection);
        }

        $this->toPostDomainEntity($post, $postEntityCollection, $commentEntityCollection);

        return $postEntityCollection->first();
    }

    /**
     * 投稿のEloquentモデルをドメインエンティティに変換する
     *
     * @param Post $post
     * @param Collection $postEntityCollection
     * @param Collection $commentEntityCollection
     */
    private function toPostDomainEntity(Post $post, Collection $postEntityCollection, Collection $commentEntityCollection)
    {
        /** @var Post $post */
        $comments = collect();
        foreach ($commentEntityCollection as $commentEntity) {
            if ($post->getAttribute('id') == $commentEntity->getPostId()) {
                $comments = $commentEntityCollection;
            }
        }

        $postEntity = Entities\Post::reConstruct(
            $post->getAttribute('id'),
            $post->getAttribute('title'),
            $post->getAttribute('body'),
            $post->getAttribute('status'),
            $comments,
            $post->getAttribute('created_at'),
            $post->getAttribute('updated_at'),
        );
        $postEntityCollection->push($postEntity);
    }

    /**
     * コメントのEloquentモデルをドメインエンティティに変換する
     *
     * @param Comment $comment
     * @param Collection $commentEntityCollection
     */
    private function toCommentDomainEntity(Comment $comment, Collection $commentEntityCollection)
    {
        $commentEntity = Entities\Comment::reConstruct(
            $comment->getAttribute('id'),
            $comment->getAttribute('post_id'),
            $comment->getAttribute('title'),
            $comment->getAttribute('body'),
            $comment->getAttribute('status'),
            $comment->getAttribute('created_at'),
            $comment->getAttribute('updated_at'),
        );
        $commentEntityCollection->push($commentEntity);
    }
}
