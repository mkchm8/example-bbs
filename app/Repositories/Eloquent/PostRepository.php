<?php

namespace App\Repositories\Eloquent;

use App\DataAccess\Eloquent\EloquentModel;
use App\DataAccess\Eloquent\Post;
use App\Domain\Entities\Comment as CommentEntity;
use App\Domain\Entities\DomainEntity;
use App\Domain\Entities\Post as PostEntity;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
     * TODO: 条件による絞り込みができるようにする
     *
     * @param array $conditions
     * @return Collection
     */
    public function findListWithComments(array $conditions): Collection
    {
        $posts = $this->post::with('comments')->get();

        $postEntityCollection = collect();
        $this->toDomainEntity($posts, $postEntityCollection, new PostEntity());

        foreach ($posts as $post) {
            $comments = $post->comments()->get();
            $commentEntityCollection = collect();
            if ($comments->isNotEmpty()) {
                $this->toDomainEntity($comments, $commentEntityCollection, new CommentEntity());
            }

            foreach ($postEntityCollection as $postEntity) {
                if ($postEntity->id == $post->id) {
                    $postEntity->setComments($commentEntityCollection);
                }
            }
        }

        return $postEntityCollection;
    }

    /**
     * Eloquentモデルをドメインエンティティに変換する
     * TODO: setterをやめて値オブジェクトを導入する
     *
     * @param \Illuminate\Database\Eloquent\Collection $eloquents
     * @param Collection $domainEntityCollection
     * @param DomainEntity $domainEntity
     * @return void
     */
    private function toDomainEntity(
        \Illuminate\Database\Eloquent\Collection $eloquents,
        Collection $domainEntityCollection,
        DomainEntity $domainEntity
    )
    {
        $attributes = $eloquents->first()->getAttributes();
        $columns = array_keys($attributes);

        /** @var EloquentModel $eloquent */
        foreach ($eloquents as $eloquent) {
            $entity = clone $domainEntity;
            foreach ($columns as $column) {
                $eloquent->getAttribute($column);
                $setter = 'set' . Str::studly($column);
                if (method_exists($entity, $setter)) {
                    $entity->$setter($eloquent->getAttribute($column));
                }
            }
            $domainEntityCollection->push($entity);
        }
    }
}
