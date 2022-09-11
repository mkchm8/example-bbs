<?php

namespace App\Usecases;

use App\Domain\Entities\Post;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Support\Collection;
use App\Domain\Enums\Post\Status as PostStatus;
use App\Domain\Enums\Comment\Status as CommentStatus;

class PostApplicationService
{
    /**
     * @var PostRepositoryInterface
     */
    protected PostRepositoryInterface $postRepository;

    /**
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * 投稿リストをコメント付きで取得する
     *
     * @param array $inputs
     * @return Collection
     */
    public function getListWithComments(array $inputs): Collection
    {
        return $this->postRepository->findListWithComments($inputs);
    }

    /**
     * 承認済みの投稿リストをコメント付きで取得する
     *
     * @return Collection
     */
    public function getApprovedListWithComments(): Collection
    {
        return $this->postRepository->findListWithComments([
            'postStatus' => PostStatus::Approved->value,
            'commentStatus' => CommentStatus::Approved->value,
        ]);
    }

    /**
     * 承認済み投稿をコメント付きで取得する
     *
     * @param int $postId
     * @return Post
     */
    public function getByIdWithApprovedComments(int $postId): Post
    {
         return $this->postRepository->findByIdWithComments(
             $postId,
             ['commentStatus' => CommentStatus::Approved->value]
         );
    }
}
