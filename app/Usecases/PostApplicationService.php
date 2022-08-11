<?php

namespace App\Usecases;

use App\Repositories\PostRepositoryInterface;
use Illuminate\Support\Collection;

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
}
