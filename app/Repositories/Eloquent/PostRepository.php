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
     * TODO: ステータス以外でも絞り込みできるようにする
     * TODO: $conditions がチェックされていないので、名前付き引数で個別に受け取るように変更するか$conditionsを丸ごとオブジェクト化するなりして契約に基づいた作りにする（後者は名前付き引数が使えなかった頃の次善の策という感じがするので、前者が良さそう）
     *
     * @param int $postId
     * @param ?array $conditions
     * @return Entities\Post
     */
    public function findByIdWithComments(int $postId, ?array $conditions = null): Entities\Post
    {
        $post = $this->post::with([
            'comments' => function ($query) use ($conditions) {
                if (isset($conditions['commentStatus'])) {
                    $query->filterStatus($conditions['commentStatus']);
                }
            }])->get()->find($postId);
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
     * TODO: 名前と実態が乖離しているので修正する（Entityに変換すると言いつつ、CollectionにEntityをpushしている）
     * TODO: 実装箇所はここではない気がしている。変換用のクラスを作る？
     *
     * @param Post $post
     * @param Collection $postEntityCollection
     * @param Collection $commentEntityCollection
     */
    protected function toPostDomainEntity(Post $post, Collection $postEntityCollection, Collection $commentEntityCollection)
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
     * TODO: 名前と実態が乖離しているので修正する（Entityに変換すると言いつつ、CollectionにEntityをpushしている
     * TODO: 実装箇所はここではない気がしている。変換用のクラスを作る？
     *
     * @param Comment $comment
     * @param Collection $commentEntityCollection
     */
    protected function toCommentDomainEntity(Comment $comment, Collection $commentEntityCollection)
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
