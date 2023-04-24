<?php

namespace App\Repositories\Eloquent;

use App\DataAccess\Eloquent\Comment;
use App\DataAccess\Eloquent\Post;
use App\Domain\Entities;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Support\Collection;

class PostRepository implements PostRepositoryInterface
{
    // TODO: Postだとわかりにくいので、EloquentをuseするようにしてEloquent\Post に修正する
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
     * TODO: ステータス以外でも絞り込みできるようにする
     * TODO: $conditions がチェックされていない。名前付き引数に変更するか引数自体をオブジェクト化して契約に基づいた作りにする。
     *
     * @param array $conditions
     * @return Collection
     */
    public function findListWithComments(array $conditions): Collection
    {
        $posts = $this->post::with([
            'comments' => function ($query) use ($conditions) {
                $query->where('status', $conditions['commentStatus']);
            }])
            ->where('status', $conditions['postStatus'])
            ->get();

        $postEntityCollection = $posts->map(
            fn(Post $post) => Entities\Post::reConstruct(
                $post->id,
                $post->title,
                $post->body,
                $post->status,
                $post->comments->map(
                    fn(Comment $comment) => Entities\Comment::reConstruct(
                        $comment->id,
                        $comment->post_id,
                        $comment->title,
                        $comment->body,
                        $comment->status,
                        $comment->created_at,
                        $comment->updated_at,
                    )
                ),
                $post->created_at,
                $post->updated_at,
            )
        );

        return $postEntityCollection;
    }

    /**
     * 投稿をコメント付きで取得する
     * TODO: ステータス以外でも絞り込みできるようにする
     * TODO: $conditions がチェックされていないので、名前付き引数で個別に受け取るように変更するか$conditionsを丸ごとオブジェクト化するなりして契約に基づいた作りにする（後者は名前付き引数が使えなかった頃の次善の策という感じがするので、前者が良さそう）
     *
     * @param int $postId
     * @param ?array $conditions
     * @return Entities\Post
     */
    public function findByIdWithComments(int $postId, ?array $conditions = null): Entities\Post
    {
        $posts = $this->post::with([
            'comments' => function ($query) use ($conditions) {
                $query->when($conditions['commentStatus'], function ($query, $commentStatus) {
                    $query->where('status', $commentStatus);
                });
            }])
            ->where('id', $postId)
            ->get();

        $postEntity = $posts->map(
            fn(Post $post) => Entities\Post::reConstruct(
                $post->id,
                $post->title,
                $post->body,
                $post->status,
                $post->comments->map(
                    fn(Comment $comment) => Entities\Comment::reConstruct(
                        $comment->id,
                        $comment->post_id,
                        $comment->title,
                        $comment->body,
                        $comment->status,
                        $comment->created_at,
                        $comment->updated_at,
                    )
                ),
                $post->created_at,
                $post->updated_at,
            )
        )->first();

        return $postEntity;
    }
}
